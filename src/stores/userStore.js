import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('userStore', {
    state: () => ({
        email: '',
        username: '',
        uid: null,
        token: ''
    }), 
    getters: {
        loggedIn: (state) => state.email != '',
        getUsername: (state) => state.username,
        getToken: (state) => state.token
    },
    actions: {
        login(email, passwd) {
            return new Promise((resolve, reject) => {
            axios
                .post('/auth', {
                mail: email,
                passwd: passwd,
                })
                .then((res) => {
                this.email = res.data.user.email;
                this.token = res.data.token;
                this.uid = res.data.user.id;
                axios.defaults.headers.common['Authorization'] =
                    'Bearer ' + res.data.token;
                resolve();
                });
            }).catch((error) => reject(error));
        },
        logout() {
            this.email = ''
            this.token = ''
            this.uid = ''
            delete axios.defaults.headers.common['Authorization'];
        }
    }
})
