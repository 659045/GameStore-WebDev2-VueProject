<template>
    <header>
        <NavBar />
    </header>

    <div class="content-container p-5">
        <div>
            <h1>User Details</h1>
            <div class="form-group d-flex flex-column">
                <label for="email">Email</label>
                <input type="email" class="form-control" v-model="user.email" placeholder="Enter email" required>
                <label for="username">Username</label>
                <input type="text" class="form-control" v-model="user.username" placeholder="Enter username" required>
                <label for="password">Password</label>
                <input type="password" class="form-control" v-model="password" placeholder="Enter Password" required>
                <label v-show="errorMessage" class="text-center w-100 alert alert-danger">{{ errorMessage }}</label>     
                <label v-show="successMessage" class="text-center w-100 alert alert-success">{{ successMessage }}</label>
                <button class="btn btn-primary mt-3 mx-auto" @click="editUser()">Edit</button>
                <button class="btn btn-danger mt-5 mb-3 mx-auto" @click="deleteUser()">Delete Account</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default {
    name: 'User',
    components: {
        NavBar,
    },
    data() {
        return {
        user: {
            id: null,
            username: '',
            email: '',
            password: ''
        },
        errorMessage: '',
        successMessage: '',
        password: ''
      };
    },
    mounted() {
        this.getUser();
        console.log(this.user.id);
    },
    methods: {
        async getUser() {
            try {
                const response = await axios.get('http://localhost/api/user', {
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        username: localStorage.getItem('username')
                    }
                })

                if (response.status === 200) {
                    this.user = response.data;
                } else {
                    this.errorMessage = 'Error getting user';
                    this.resetMessages();
                } 
            } catch (error) {
                this.errorMessage = error.response.data.message;
            }
        },
         async editUser() {
            try {
                const response = await axios.post('http://localhost/api/user', {
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    id: this.user.id,
                    email: this.user.email,
                    username: this.user.username,
                    password: this.password
                })

                if (response.status === 200) {
                    this.errorMessage = '';
                    this.successMessage = 'User edited successfully';
                    this.resetMessages();
                } else {
                    this.errorMessage = 'Error editing user';
                    this.resetMessages();
                } 
            } catch (error) {
                this.errorMessage = error.response.data.message;
            }
        },
        async deleteUser() {
            try {
                const response = await axios.delete('http://localhost/api/user', {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    data: {
                        id: this.user.id
                    }
                })

                if (response.status === 200) {
                    axios.post('http://localhost/api/logout').then(() => {
                        localStorage.clear();
                        window.location.href = '/';
                    });
                } else {
                    this.errorMessage = 'Error deleting user';
                    this.resetMessages();
                } 
            } catch (error) {
                this.errorMessage = error.response.data.message;
            }
        },
        resetMessages() {
            setTimeout(() => {
                this.errorMessage = '';
                this.successMessage = '';
            }, 3000);
        },
    }
};

</script>

<style scoped>
.content-container {
    height: 100vh;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.alert {
    transition: opacity 1s ease-in-out;
}

.form-group {
        width: 25%;
        margin: 8% auto;
}

.form-control {
    margin-bottom: 10%;
}

.content-container {
    height: 100vh;
}

.btn {
    width: 40%;
}
</style>
