from sqlalchemy import Column, Integer, String, Float, Date, ForeignKey, DateTime, Boolean
from app.database import Base
from datetime import datetime

class User(Base):
    __tablename__ = "users"

    id = Column(Integer, primary_key=True, index=True)
    username = Column(String, unique=True, index=True)
    email = Column(String, unique=True, index=True)
    hashed_password = Column(String)
    is_active = Column(Boolean, default=True)

class BuyersPreference(Base):
    __tablename__ = "buyer_preferences"

    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey('users.id'))
    preferred_weight = Column(Float)
    age_of_pigs = Column(String(50))
    price_range = Column(String(50))

class PigFarmInformation(Base):
    __tablename__ = "pig_farm_information"

    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey('users.id'))
    feeding_type = Column(String)
    frequency_of_feeding = Column(String)
    min_price_per_kilo = Column(Float)
    max_price_per_kilo = Column(Float)
    latitude = Column(Float)
    longitude = Column(Float)

class Pig(Base):
    __tablename__ = "pigs"

    pigId = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey('users.id'))
    weight = Column(Float)
    date_of_birth = Column(Date)
    status = Column(String)
    image = Column(String)

class UserInteraction(Base):
    __tablename__ = "user_interactions"

    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey('users.id'))
    pig_id = Column(Integer, ForeignKey('pigs.pigId'))
    interaction_time = Column(DateTime, default=datetime.utcnow)