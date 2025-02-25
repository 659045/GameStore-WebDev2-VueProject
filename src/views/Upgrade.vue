<template>
    <header>
        <NavBar />
    </header>

    <div class="content-container p-5">
        <h1>Upgrade to premium</h1>
        <h2>Get Wish list feature</h2>

        <div class="mt-5">
            <div class="form-group d-flex flex-column">
                <input type="hidden" class="form-control" id="idInput" name="id" value="<? echo $user->getId(); ?>">
                <h2 class="mx-auto">Only €9.99!! One time payment!!</h2>
                <button id="upgradeButton" class="btn btn-primary mt-3 mx-auto" @click="upgradeUser">Upgrade to premium</button>
                <label v-show="errorMessage" class="text-center w-100 alert alert-danger">{{ errorMessage }}</label>     
                <label v-show="successMessage" class="text-center w-100 alert alert-success">{{ successMessage }}</label>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default {
    name: 'Upgrade',
    components: {
        NavBar
    },
    data() {
        return {
            errorMessage: '',
            successMessage: '',
        };
    },
    methods: {
        resetMessages() {
            setTimeout(() => {
                this.errorMessage = '';
                this.successMessage = '';
            }, 3000);
        },
        async upgradeUser() {
            try {
                const response = await axios.post('http://localhost/api/upgrade', {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    id: localStorage.getItem('user_id'),
                })

                if (response.status === 200) {
                    this.fetchGames();
                    this.errorMessage = '';
                    this.successMessage = 'User successfully upgraded to premium';
                    this.resetMessages();
                } else {
                    this.errorMessage = 'Error fail to upgrade premium';
                    this.resetMessages();
                }

            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        },
    }
};
</script>

<style scoped>
.content-container {
    height: 100vh;
}

.btn {
    width: 30%;
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
</style>