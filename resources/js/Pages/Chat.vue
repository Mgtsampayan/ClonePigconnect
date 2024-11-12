
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import { ref, onMounted } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  
  const { props } = usePage();
  const userId = props.auth.user.id;
  const messages = ref([]);
  const newMessage = ref('');
  const receiverId = ref(null); // Set this to the ID of the receiver
  
  const fetchMessages = async () => {
    try {
      const response = await fetch('/api/chat/messages', {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${props.auth.user.api_token}`,
        },
      });
  
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
  
      const data = await response.json();
      messages.value = data;
    } catch (error) {
      console.error('Error fetching messages:', error);
    }
  };
  
  const sendMessage = async () => {
    if (!newMessage.value.trim()) return;
  
    try {
      const response = await fetch('/api/chat/messages', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${props.auth.user.api_token}`,
        },
        body: JSON.stringify({
          receiver_id: receiverId.value,
          message: newMessage.value,
        }),
      });
  
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
  
      const data = await response.json();
      messages.value.push(data);
      newMessage.value = '';
    } catch (error) {
      console.error('Error sending message:', error);
    }
  };
  
  onMounted(() => {
    fetchMessages();
  });
  </script>
  
  <template>
      <Head title="Dashboard" />
  
      <AuthenticatedLayout>
          <template #header>
              <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Chat</h2>
          </template>
  
          <div class="chat-container">
      <div class="chat-messages">
        <div v-for="message in messages" :key="message.id" class="chat-message">
          <div :class="{'chat-message-sent': message.sender_id === userId, 'chat-message-received': message.sender_id !== userId}">
            <p>{{ message.message }}</p>
          </div>
        </div>
      </div>
      <div class="chat-input">
        <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message..." />
        <button @click="sendMessage">Send</button>
      </div>
    </div>
      </AuthenticatedLayout>
  </template>
  
  
  <style>
  .chat-container {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  
  .chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
  }
  
  .chat-message {
    margin-bottom: 10px;
  }
  
  .chat-message-sent {
    text-align: right;
  }
  
  .chat-message-received {
    text-align: left;
  }
  
  .chat-input {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ccc;
  }
  
  .chat-input input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .chat-input button {
    margin-left: 10px;
    padding: 10px 20px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    cursor: pointer;
  }
  </style>