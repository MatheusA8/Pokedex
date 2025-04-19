<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pokédex Ultra</title>
  <style>
    /* Fontes */
    @import url('https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap');
    @import url('https://fonts.cdnfonts.com/css/pokemon-solid');

    /* Reset rápido */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Rubik', sans-serif;
      background-image: 
        url('https://img.freepik.com/vetores-gratis/fundo-de-efeito-de-zoom-gradiente_23-2149722799.jpg?semt=ais_hybrid&w=740'),
        linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      background-size: cover;
      background-position: center center;
      background-attachment: fixed;
      background-blend-mode: overlay;
      color: #fff;
      display: flex;
      flex-direction: column;
    }
    header {
      text-align: center;
      padding: 2rem 1rem 3rem;
    }
    header h1 {
      font-family: 'Pokemon Solid', sans-serif;
      font-size: 3.5rem;
      color: #ffcb05;
      text-shadow: 2px 2px #0008;
      margin-bottom: 1rem;
    }

    .controls {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.75rem;
      flex-wrap: wrap;
      margin-bottom: 2rem;
    }
    .controls button,
    .controls input {
      border: none;
      border-radius: 999px;
      font-size: 1rem;
      outline: none;
    }
    .controls button {
      background-color: #ffc107;
      color: #000;
      padding: 0.6rem 1.2rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .controls button:hover {
      background-color: #ffdd4b;
    }
    .controls input {
      padding: 0.6rem 1rem;
      width: 250px;
    }

    main {
      flex: 1;
    }
    .pokemon-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px 50px; /* 15px vertical, 50px horizontal */
      padding: 0 2rem 2rem;
      justify-items: center;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
    }
    .pokemon-card {
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(12px);
      border-radius: 1rem;
      padding: 1rem;
      width: 200px;
      text-align: center;
      position: relative;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      transition: transform 0.3s;
    }
    .pokemon-card:hover {
      transform: translateY(-5px) scale(1.02);
    }
    .pokemon-card img {
      width: 120px;
      height: 120px;
      object-fit: contain;
    }
    .pokemon-card h2 {
      margin: 0.5rem 0;
      text-transform: capitalize;
      font-size: 1.25rem;
      color: #fff;
      text-shadow: 1px 1px #0008;
    }

    .heart-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #fff;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.2s, background 0.2s;
      font-size: 18px;
      color: #000;
    }
    .heart-btn.favorited {
      background: #e53935;
      color: #fff;
      transform: scale(1.2);
    }
  </style>
</head>
<body>

  <header>
    <h1>Pokédex Ultra</h1>
    <div class="controls">
      <button onclick="location.href='logout.php'">🚪 Sair</button>
      <button onclick="location.href='favoritos.php'">❤️ Favoritos</button>
      <input type="text" id="searchInput" placeholder="Digite nome ou número" />
      <button onclick="searchPokemon()">Buscar</button>
      <button onclick="clearResults()">Limpar</button>
    </div>
  </header>

  <main>
    <div class="pokemon-container" id="pokemonContainer"></div>
  </main>

  <script>
    // Lista principal de Pokémon exibidos
    let pokemonList = JSON.parse(localStorage.getItem('pokemonList')) || [];
    // Estado de favoritos (persistido no storage e sincronizado com o servidor)
    let favoritePokemons = JSON.parse(localStorage.getItem('favoritePokemons')) || [];

    function savePokemonList() {
      localStorage.setItem('pokemonList', JSON.stringify(pokemonList));
    }
    function saveFavoritePokemons() {
      localStorage.setItem('favoritePokemons', JSON.stringify(favoritePokemons));
    }

    async function searchPokemon() {
      const q = document.getElementById('searchInput').value.trim().toLowerCase();
      if (!q) return;
      try {
        const res = await fetch(`https://pokeapi.co/api/v2/pokemon/${q}`);
        if (!res.ok) throw '';
        const poke = await res.json();
        pokemonList.push(poke);
        savePokemonList();
        renderPokemon();
      } catch {
        alert('Pokémon não encontrado 😢');
      }
    }

    function clearResults() {
      document.getElementById('searchInput').value = '';
      pokemonList = [];
      savePokemonList();
      document.getElementById('pokemonContainer').innerHTML = '';
    }

    function renderPokemon() {
      const container = document.getElementById('pokemonContainer');
      container.innerHTML = '';
      pokemonList.forEach(pokemon => {
        const card = document.createElement('div');
        card.className = 'pokemon-card';

        const heart = document.createElement('div');
        heart.className = 'heart-btn';
        heart.innerText = '❤';

        // Se já for favorito, aplica classe
        if (favoritePokemons.includes(pokemon.name)) {
          heart.classList.add('favorited');
        }

        heart.addEventListener('click', async () => {
          // 1) Chama o servidor para salvar/remover do banco
          const form = new FormData();
          form.append('nome', pokemon.name);
          await fetch('favoritar.php', { method: 'POST', body: form });

          // 2) Atualiza localmente
          const isFav = heart.classList.toggle('favorited');
          if (isFav) {
            favoritePokemons.push(pokemon.name);
          } else {
            favoritePokemons = favoritePokemons.filter(n => n !== pokemon.name);
          }
          saveFavoritePokemons();
        });

        card.innerHTML = `
          <img src="${pokemon.sprites.front_default}" alt="${pokemon.name}">
          <h2>${pokemon.name}</h2>
        `;
        card.appendChild(heart);
        container.appendChild(card);
      });
    }

    // Ao carregar a página
    window.addEventListener('DOMContentLoaded', renderPokemon);
  </script>
</body>
</html>
