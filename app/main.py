import logging
from fastapi import FastAPI, Depends, HTTPException
from sqlalchemy.orm import Session
from datetime import datetime
from app.database import SessionLocal, engine, Base
from app.models import BuyersPreference, PigFarmInformation, Pig, UserInteraction, User

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

Base.metadata.create_all(bind=engine)

app = FastAPI()

# Dependency to get the database session
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

@app.get("/recommendations")
def get_recommendations(user_id: int, db: Session = Depends(get_db)):
    logger.info(f"Fetching recommendations for user_id: {user_id}")

    # Fetch buyer preferences
    preferences = db.query(BuyersPreference).filter(BuyersPreference.user_id == user_id).first()
    if not preferences:
        raise HTTPException(status_code=404, detail="Buyer preferences not found")

    logger.info(f"Buyer Preferences: {preferences}")

    # Fetch pigs
    pigs = db.query(Pig).all()
    logger.info(f"Total Pigs: {len(pigs)}")
    for pig in pigs:
        logger.info(f"Pig: {pig}")

    # Filter pigs based on buyer preferences
    recommended_pigs = []
    for pig in pigs:
        # Fetch farm information for each pig
        farm_info = db.query(PigFarmInformation).filter(PigFarmInformation.user_id == pig.user_id).first()
        if not farm_info:
            logger.info(f"Excluding Pig ID: {pig.pigId} because farm information not found")
            continue

        logger.info(f"Farm Information: {farm_info}")

        # Compute the age of the pig in months
        age_in_months = (datetime.now().year - pig.date_of_birth.year) * 12 + datetime.now().month - pig.date_of_birth.month
        logger.info(f"Pig ID: {pig.pigId}, Age in Months: {age_in_months}, Weight: {pig.weight}")

        # Check if the pig matches the buyer's preferences
        if preferences.age_of_pigs != "Older" and age_in_months != int(preferences.age_of_pigs):
            logger.info(f"Excluding Pig ID: {pig.pigId} due to age preference")
            continue
        if preferences.age_of_pigs == "Older" and age_in_months <= 6:
            logger.info(f"Excluding Pig ID: {pig.pigId} because it is not older than 6 months")
            continue
        if pig.weight < preferences.preferred_weight:
            logger.info(f"Excluding Pig ID: {pig.pigId} due to weight preference")
            continue
        price_range = float(preferences.price_range)
        if not (farm_info.min_price_per_kilo <= price_range <= farm_info.max_price_per_kilo):
            logger.info(f"Excluding Pig ID: {pig.pigId} due to price preference")
            continue

        # Add latitude, longitude, min_price_per_kilo, and max_price_per_kilo to the pig object for filtering
        pig.latitude = farm_info.latitude
        pig.longitude = farm_info.longitude
        pig.min_price_per_kilo = farm_info.min_price_per_kilo
        pig.max_price_per_kilo = farm_info.max_price_per_kilo

        recommended_pigs.append(pig)

    logger.info(f"Recommended Pigs: {len(recommended_pigs)}")
    if recommended_pigs:
        logger.info("Recommendations fetched successfully")
    else:
        logger.info("No recommendations found")

    return recommended_pigs

@app.post("/api/track_interaction")
def track_interaction(user_id: int, pig_id: int, db: Session = Depends(get_db)):
    interaction = UserInteraction(user_id=user_id, pig_id=pig_id)
    db.add(interaction)
    db.commit()
    return {"message": "Interaction tracked successfully"}