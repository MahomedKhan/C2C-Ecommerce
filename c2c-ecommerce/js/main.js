$(document).ready(function () {
  // Load sample products
  loadProducts();

  function loadProducts() {
    $.get("php/products/get_products.php", function (data) {
      let products = JSON.parse(data);
      let productHTML = '';
      products.forEach(p => {
        productHTML += `
          <div class="col-md-4">
            <div class="card card-product">
              <img src="img/${p.image}" class="card-img-top" alt="${p.title}">
              <div class="card-body">
                <h5 class="card-title">${p.title}</h5>
                <p class="card-text">${p.description.substring(0, 60)}...</p>
                <p class="text-success">R${p.price}</p>
                <a href="product.html?id=${p.id}" class="btn btn-primary">View</a>
                <button class="btn btn-success add-to-cart" data-id="${p.id}">Add to Cart</button>
              </div>
            </div>
          </div>`;
      });
      $('#product-list').html(productHTML);
    });
  }

  // Filter/sort products
  $('#sortOptions').change(function () {
    // Trigger backend sort request (for now: reload)
    loadProducts();
  });

  // Search handler
  $('#search-form').submit(function (e) {
    e.preventDefault();
    let keyword = $('#searchInput').val().toLowerCase();
    $('.card-title').each(function () {
      $(this).closest('.col-md-4').toggle($(this).text().toLowerCase().includes(keyword));
    });
  });
});
