<template>
    <header>
        <NavBar />
    </header>

    <div class="content-container p-5">
        <h1>Upgrade to premium</h1>
        <h2>Get Wish list feature</h2>

        <div class="mt-5">
            <div class="d-flex flex-column">
                <h2 class="mx-auto">Only €9.99!! One time payment!!</h2>
                <button v-if="!upgradeSuccess" class="btn btn-primary mt-3 mb-3 mx-auto" @click="upgradeUser">Upgrade to premium</button>
                <button v-else class="btn btn-primary mt-3 mb-3 mx-auto" @click="backToHome">Back to Home</button>
                <label v-show="errorMessage" class="text-center w-25 mx-auto alert alert-danger">{{ errorMessage }}</label>     
                <label v-show="successMessage" class="text-center w-25 alert mx-auto alert-success">{{ successMessage }}</label>
                
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
            upgradeSuccess: false,
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
                let formData = new FormData();
                formData.append('id', localStorage.getItem('user_id'));

                const response = await axios.post('http://localhost/api/upgrade', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                })

                if (response.status === 200) {
                    this.errorMessage = '';
                    this.successMessage = 'User successfully upgraded to premium';
                    localStorage.setItem('role', 'premium');
                    this.upgradeSuccess = true;
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
        backToHome() {
            this.$router.push('/');
        }
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