function criaCard(poke) {
  if (exibidos.includes(poke.name)) return;
  exibidos.push(poke.name);

  const tipos = poke.types.map(t => t.type.name).join(', ');
  const isFav = favoritos.includes(poke.name);

  const card = document.createElement('div');
  card.className = 'card';
  if (isFav) card.classList.add('favorited-card'); // Aplica visual no card se for favorito

  card.innerHTML = `
    <button class="fav-btn ${isFav ? 'favorited' : ''}" data-name="${poke.name}">❤️</button>
    <img src="${poke.sprites.front_default}" alt="${poke.name}">
    <h2>${poke.name}</h2>
    <p>Tipo: ${tipos}</p>
  `;
  container.appendChild(card);

  const btn = card.querySelector('.fav-btn');
  btn.addEventListener('click', () => {
    if (!userLoggedIn) {
      alert('Faça login para favoritar.');
      return;
    }
    fetch('salvar_favoritos.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pokemon: poke.name })
    })
    .then(r => r.json())
    .then(data => {
      if (data.status === 'ok') {
        if (data.acao === 'favoritado') {
          btn.classList.add('favorited');
          card.classList.add('favorited-card');
          favoritos.push(poke.name);
        } else {
          btn.classList.remove('favorited');
          card.classList.remove('favorited-card');
          favoritos = favoritos.filter(n => n !== poke.name);
        }
      } else {
        alert(data.mensagem || 'Erro inesperado');
      }
    });
  });
}
