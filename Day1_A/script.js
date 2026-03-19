const getTimeRemaining = (product) => {
    const s = product.time_left % 60;
    const m = ((product.time_left - s) / 60) % 60;
    const h = Math.floor((product.time_left - m * 60) / 3600);
    return (h ? `${h}h ` : "") + (true ? `${m}m ` : "") + `${s}s`
}

const openOverlay = (product) => {
  const elem = document.getElementById("product-overlay").content.cloneNode(true);
  elem.querySelector("h2").innerText = product.title;
  elem.querySelector("p").innerText = product.description;
  const img = elem.querySelector(".product-image");
  img.src = "/mediafiles/images/" + product.image + ".jpg";
  img.alt = product.title;
  const timer = elem.querySelector(".time-left :last-child")
  timer.innerText = getTimeRemaining(product)
  setInterval(() => {
    product.time_left -= 1
    timer.innerText = getTimeRemaining(product)
  }, 1000)
  const ul = elem.querySelector("ul");
  if (product.bids.length) {
      product.bids.sort((a,b) => b.placed_at - a.placed_at).forEach((b) => {
      const li = document.createElement("li");
      li.innerText = new Date(Number(b.placed_at)).toLocaleString("en-CH") + ": £" + b.amount + ".-" 
      ul.appendChild(li)
    })
  } else {
      const li = document.createElement("li");
      li.innerText = `No bids yet. Minimum price: £${product.minimal_price}.-`
      ul.appendChild(li)
  }
  elem.querySelector(".close-overlay").addEventListener("click", () => {
    document.querySelector("main").removeChild(document.querySelector(".overlay"))
  })
  elem.querySelector('.bids form').addEventListener("submit", async (e) => {
    const amount = e.target.querySelector('.bids input[name="amount"]').value;
    if (!amount) {
      return;
    }
    const res = await fetch("https://skillsfinaale26.canvasaccept.com/api/bid", {
      method: "POST",
      body: JSON.stringify({ product_id: product.id, amount: Number(amount) }),
      headers: { "Content-Type": "application/json"}
    });
    const data = await res.json();
    if (data.status == "bid_placed") {
      document.querySelector("main").removeChild(document.querySelector(".overlay"))
    }
    applyData(data.products)
    e.preventDefault()
  })
  document.querySelector("main").appendChild(elem);
}

const addProduct = (product, parent) => {
  const elem = document.getElementById("product-template").content.cloneNode(true);
  elem.querySelector("h4").innerText = product.title;
  const img = elem.querySelector(".product-image");
  img.src = "/mediafiles/images/" + product.image + ".jpg";
  img.alt = product.title;
  elem.querySelector("p").innerText = product.intro;
  const timer = elem.querySelector(".time-left :last-child")
  timer.innerText = getTimeRemaining(product)
  setInterval(() => {
    product.time_left -= 1
    timer.innerText = getTimeRemaining(product)
  }, 1000)
  const bids = product.bids.sort((a, b) => b.amount - a.amount);
  elem.querySelector(".current-bid strong").innerText = bids && bids[0] ? `£ ${bids[0]["amount"]}.-` : '-';
  elem.querySelector(".open-button").addEventListener("click", () => openOverlay(product))
  parent.appendChild(elem);
}

const setProducts = (data) =>  {
  let products = data.sort((a, b) => a.time_left - b.time_left);
  if (location.hash) {
    document.getElementById("search").value = ""
    products = products.filter((p) => p.category.title.toLowerCase() === location.hash.replace("#", "").toLocaleLowerCase());
  } else if (document.getElementById("search").value.length >= 3) {
    const searchValue = document.getElementById("search").value.toLowerCase()
    products = products.filter((p) => p.title.toLowerCase().includes(searchValue) || p.intro.toLowerCase().includes(searchValue));
  }
  products = products.slice(0, 8);
  const parent = document.getElementById("products");
  parent.innerHTML = "";
  products.forEach((p) => addProduct(p, parent));
}

const applyData = (data) => {
  const randomFeaturedIds = [];
  for (let i = 0; i < 3; i++) {
    let r;
    do {
      r = Math.floor(Math.random() * data.length)
    } while (randomFeaturedIds.includes(r));
    randomFeaturedIds.push(r);
  }
  const randomFeatured = randomFeaturedIds.map((i) => data[i]);
  const parent = document.getElementById("featured");
  parent.innerHTML = "";
  randomFeatured.forEach((p) => addProduct(p, parent));
  setProducts(data);
  window.onhashchange = () => setProducts(data);
}

const setup = async () => {
  const data = await fetch("https://skillsfinaale26.canvasaccept.com/api/products").then(r => r.json());
  console.log(data);
  applyData(data);
  document.getElementById("search").addEventListener("input", (e) => {
    const value = e.target.value;
    if (value.length < 3) {
      return
    }
    location.hash = "";
    setProducts(data);
  })
}
setup();