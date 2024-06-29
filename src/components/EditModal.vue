<template>
    <div>
      <form @submit.prevent="handleSubmit">
        <input type="hidden" id="idField" v-model="game.id" required>
        <label for="title">Title:</label>
        <input type="text" id="titleInput" v-model="game.title" required>
        <label for="description">Description:</label>
        <input type="text" id="descriptionInput" v-model="game.description" required>
        <label for="price">Price:</label>
        <input type="text" id="priceInput" v-model="game.price" pattern="[0-9]+(\.[0-9]+)?" required>
        <label for="image">Image:</label>
        <input type="file" id="imageInput" @change="handleImageUpload" accept="image/*" required><br><br>
        <button type="submit" class="btn btn-primary">Edit</button>
      </form>
      <label v-if="errorMessage" class="label">{{ errorMessage }}</label>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      game: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        gameData: {
          id: this.game.id,
          title: this.game.title,
          description: this.game.description,
          price: this.game.price,
          image: null,
        },
        errorMessage: ''
      }
    },
    methods: {
      handleImageUpload(event) {
        this.gameData.image = event.target.files[0];
      },
      async handleSubmit() {
        try {
          const formData = new FormData();
          formData.append('id', this.gameData.id);
          formData.append('title', this.gameData.title);
          formData.append('description', this.gameData.description);
          formData.append('price', this.gameData.price);
          formData.append('image', this.gameData.image);
  
          const response = await fetch('/game', {
            method: 'POST',
            body: formData,
          });
  
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
  
          this.$router.push('/game');
        } catch (error) {
          console.error('Error:', error);
          this.errorMessage = 'Error editing game';
        }
      }
    }
  }
  </script>
  
  <style scoped>
  </style>
  