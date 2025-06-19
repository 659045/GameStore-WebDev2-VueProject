<template>
    <header>
        <NavBar />
    </header>
    <div class="content-container p-5">
        <h1>Owned Games</h1>
        <div class="game-grid mt-5">
            <GameItem v-for="game in games" :game="game" :ownedGames="games"/>
        </div>
        <label v-show="errorMessage" class="label mx-auto">{{ errorMessage }}</label>
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';
import GameItem from '../components/GameItem.vue';

export default {
    name: 'OwnedGames',
    data() {
      return {
        games: [],
        errorMessage: '',
      };
    },
    components: {
        GameItem,
        NavBar,
    },
    mounted() {
      this.fetchOwnedGames();
    },
    methods: {
      fetchOwnedGames() {
        try {
          const user_id = localStorage.getItem('user_id');

          axios.get(`http://localhost/api/owned?user_id=${user_id}`, {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
          }).then((response) => {
            if (response.data === 0) {
              this.errorMessage = 'You do not own any games';
            }

            response.data.forEach((game) => {
              axios.get(`http://localhost/api/game?id=${game.game_id}`, {
                headers: {
                  'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
              }).then((response) => {
                this.games.push(response.data);
              });
            });
          });
        } catch (error) {
          this.errorMessage = 'Error fetching owned games';
        }
      }
    },
}
</script>

<style scoped>
  .content-container {
    height: 150vh;
    width: 100%;
  }

  .game-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
  }
</style>