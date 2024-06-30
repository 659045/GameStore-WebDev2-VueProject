<template>
    <header>
        <NavBar />
    </header>
    <div class="content-container p-5">
        <h1>Game Management</h1>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <transition name="fade">
                            <td v-if="errorMessage" colspan="6"><label v-show="errorMessage" class="text-center w-100 alert alert-danger">{{ errorMessage }}</label></td>      
                        </transition>    
                    </tr>
                    <tr>
                        <transition name="fade">
                            <td v-if="successMessage" colspan="6"><label v-show="successMessage" class="text-center w-100 alert alert-success">{{ successMessage }}</label></td>
                        </transition>
                    </tr>
                    <td><input type="text" v-model="game.title" name="title" required></td>
    
                    <td><input type="text" v-model="game.description" name="description" required></td>

                    <td><input type="float" v-model="game.price" pattern="[0-9]+(\.[0-9]+)?" name="price" required></td>

                    <td><input type="file" @change="onFileChange" name="image" accept="image/*"></td>

                    <td colspan="2">
                        <button type="submit" class="btn btn-primary" @click="addGame()">Add Game</button>
                    </td>
                    
                    <tr v-for="game in games" :key="game.id">
                        <td>{{ game.title }}</td>
                        <td>{{ game.description }}</td>
                        <td>{{ game.price }}</td>
                        <td>{{ game.image }}</td>
                        <td>
                            <button class="btn btn-primary" @click="editGame(game.id)">Edit</button>
                        </td>
                        <td>
                            <button class="btn btn-danger" @click="deleteGame(game.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default {
    name: 'GameManagement',
    components: {
        NavBar,
    },
    data() {
        return {
            game: {
                title: '',
                description: '',
                price: 0.0,
                image: null,
            },
            games: [],
            errorMessage: '',
            successMessage: '',
        };
    },
    mounted() {
        try {
            axios.get('http://localhost/api/game').then((response) => {
                this.games = response.data;
            });
        } catch (error) {
            this.errorMessage = 'Error fetching games';
            this.resetMessages();
        }
    },
    methods: {
        async addGame() {
            try {
                let formData = new FormData();
                formData.append('title', this.game.title);
                formData.append('description', this.game.description);
                formData.append('price', this.game.price);
                formData.append('image', this.game.image);

                const response = await axios.post('http://localhost/api/game', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
            
                if (response.status === 201) {
                    this.games.push(response.data);
                    this.errorMessage = '';
                    this.successMessage = 'Game added successfully';
                    this.resetMessages();
                } else {
                    this.errorMessage = 'Error adding game';
                    this.resetMessages();
                }
            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        
        },
        async editGame(gameId) {
            try {
                let formData = new FormData();
                formData.append('id', gameId);
                formData.append('title', this.game.title);
                formData.append('description', this.game.description);
                formData.append('price', this.game.price);
                formData.append('image', this.game.image);

                const response = axios.post('http://localhost/api/game?', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })

                if (response.status === 200) {
                    this.games = this.games.map((game) => {
                        if (game.id === gameId) {
                            return response.data;
                        }

                        return game;
                    });
                    
                    this.errorMessage = '';
                    this.successMessage = 'Game edited successfully';
                    this.resetMessages();
                } else {
                    this.errorMessage = 'Error editing game';
                    this.resetMessages();
                }
            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        },
        async deleteGame(gameId) {
            try {
                const response = await axios.delete('http://localhost/api/game', {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    data: {
                        id: gameId,
                    },
                })

                if (response.status === 200) {
                    this.games = this.games.filter((game) => game.id !== id);
                    this.errorMessage = '';
                    this.successMessage = 'Game deleted successfully';
                    this.resetMessages();
                } else {
                    this.errorMessage = 'Error deleting game';
                    this.resetMessages();
                }
            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        },
        onFileChange(event) {       
            this.game.image = event.target.files[0];
        },
        resetMessages() {
            setTimeout(() => {
                this.errorMessage = '';
                this.successMessage = '';
            }, 3000);
        },
    },
};

</script>

<style scoped>
.content-container {
    height: 100vh;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}

.alert {
    transition: opacity 1s ease-in-out;
}
</style>
