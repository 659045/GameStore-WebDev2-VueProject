import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useGameStore = defineStore('gameStore', {
    state: () => ({
        title: '',
        description: '',
        price: 0,
        image: '',
    }),
    getters: {
        getTitle: (state) => state.title,
        getDescription: (state) => state.description,
        getPrice: (state) => state.price,
        getImage: (state) => state.image,
    },
    actions: {
        fetchGames() {
            return new Promise((resolve, reject) => {
                axios
                    .get('/game')
                    .then((res) => {
                        this.title = res.data.title;
                        this.description = res.data.description;
                        this.price = res.data.price;
                        this.image = res.data.image;
                        resolve();
                    });
            }).catch((error) => reject(error));
        },
    }
})
