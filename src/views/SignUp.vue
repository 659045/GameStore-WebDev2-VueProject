<template>
    <div>
      <head>
        <title>Sign Up</title>
      </head>
  
      <form @submit.prevent="handleSignUp">
        <div class="form-group d-flex flex-column">
          <label for="email">Email address</label>
          <input type="email" class="form-control" v-model="formData.email" placeholder="Enter email" required>
          <label for="username">Username</label>
          <input type="text" class="form-control" v-model="formData.username" placeholder="Enter username" required>
          <label for="password">Password</label>
          <input type="password" class="form-control" v-model="formData.password" placeholder="Password" required>
          <a href="/login" class="mx-auto"><small>Already have an account?</small></a>
          <button type="submit" class="btn btn-primary mt-3 mx-auto">Sign Up</button>
          <label v-if="errorMessage" class="label mx-auto mt-3">{{ errorMessage }}</label>
        </div>
      </form>
    </div>
  </template>
  
<script>
export default {
    data() {
        return {
        formData: {
            email: '',
            username: '',
            password: ''
        },
        errorMessage: ''
        };
    },
    methods: {
        async handleSignUp() {
            try {
                const response = await this.checkUserExists(this.formData.username);
                if (response.user) {
                    this.errorMessage = 'User already exists';
                } else {
                await this.createUser();
                    window.location.href = '/login'; // Redirect after successful sign up
                }
            } catch (error) {
                console.error(error);
                this.errorMessage = 'Error creating user';
            }
        },
        async createUser() {
            try {
                const response = await fetch('/user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });
                if (!response.ok) {
                    throw new Error('Failed to create user');
                }
            } catch (error) {
                throw new Error('Error creating user');
            }
        },
        async checkUserExists(username) {
            try {
                const response = await fetch(`/user?username=${username}`);
                return await response.json();
            } catch (error) {
                throw new Error('Error checking user existence');
            }
        }
    }
};
</script>

<style scoped>
    .form-group {
    width: 25%;
    margin: 8% auto;
    }

    .form-control {
    margin-bottom: 10%;
    }

    .btn {
    width: 30%;
    }
</style>
  