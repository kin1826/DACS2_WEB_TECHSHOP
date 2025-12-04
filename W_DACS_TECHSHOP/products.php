<?php
require_once 'class/product.php';
require_once 'class/product_image.php';
require_once 'class/category.php';

$productModel = new Product();
$productImgModel = new ProductImage();
$categoryModel = new Category();

$productsArr = $productModel->getAll(true);


$productsData = [];
foreach ($productsArr as $product) {
  // Lấy hình ảnh chính
  $mainImage = $productImgModel->getMainImage($product['id']);
  $imageUrl = $mainImage ? 'img/adminUP/products/' . $mainImage['image_url'] :
    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';

  // Lấy danh mục
  $category_if = $categoryModel->findById($product['category_id']);
  $categoryName = !empty($category_if['name']) ? $category_if['name'] : 'Không có danh mục';

  // Format giá
  $currentPrice = !empty($product['sale_price']) ? $product['sale_price'] : $product['regular_price'];
  $originalPrice = (!empty($product['sale_price']) && $product['sale_price'] < $product['regular_price']) ?
    $product['regular_price'] : null;

  // Lấy rating (nếu có)
  $rating = isset($product['rate']) ? round($product['rate'], 1) : 'Chưa có đánh giá';
  $reviewCount = isset($product['num_buy']) ? $product['num_buy'] : 'Chưa có lượt mua';
  $stock_status = $product['stock_status'];

  $productsData[] = [
    'id' => $product['id'],
    'name' => $product['name_pr'],
    'category' => $categoryName,
    'current_price' => number_format($currentPrice, 0, ',', '.') . 'đ',
    'original_price' => $originalPrice ? number_format($originalPrice, 0, ',', '.') . 'đ' : null,
    'rating' => $rating,
    'reviews' => $reviewCount,
    'image' => $imageUrl,
    'slug' => $product['slug'] ?? '',
    'stock_status' => $stock_status
  ];
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shop Tech</title>
  <meta name="description" content="">

  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta property="og:image:alt" content="">

  <link rel="icon" href="/favicon.ico" sizes="any">
  <link rel="icon" href="/icon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="icon.png">

  <link rel="manifest" href="site.webmanifest">
  <!--  css-->
  <link rel="stylesheet" href="css/products.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <meta name="theme-color" content="#fafafa">

</head>

<?php include 'header.php'?>

<body>

<!-- products.php -->
<div class="products-page">
  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <div class="container">
      <nav>
        <a href="index.php">Trang chủ</a>
        <span>/</span>
        <a href="products.php" class="active">Sản phẩm</a>
      </nav>
    </div>
  </div>

  <div class="container">
    <div class="products-layout">
      <!-- Sidebar Filter -->
      <aside class="filter-sidebar">
        <div class="filter-header">
          <h3><i class="fas fa-filter"></i> Bộ Lọc</h3>
          <button class="clear-filters" id="clearFilters">
            <i class="fas fa-times"></i> Xóa hết
          </button>
        </div>

        <div class="filter-content">
          <!-- Danh mục -->
          <div class="filter-group">
            <h4>Danh mục</h4>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" name="category" value="laptop">
                <span class="checkmark"></span>
                Laptop & Máy tính
              </label>
              <label class="filter-option">
                <input type="checkbox" name="category" value="smartphone">
                <span class="checkmark"></span>
                Điện thoại
              </label>
              <label class="filter-option">
                <input type="checkbox" name="category" value="tablet">
                <span class="checkmark"></span>
                Máy tính bảng
              </label>
              <label class="filter-option">
                <input type="checkbox" name="category" value="audio">
                <span class="checkmark"></span>
                Tai nghe & Loa
              </label>
              <label class="filter-option">
                <input type="checkbox" name="category" value="accessory">
                <span class="checkmark"></span>
                Phụ kiện
              </label>
            </div>
          </div>

          <!-- Giá -->
          <div class="filter-group">
            <h4>Mức giá</h4>
            <div class="filter-options">
              <label class="filter-option">
                <input type="radio" name="price" value="0-5">
                <span class="checkmark"></span>
                Dưới 5 triệu
              </label>
              <label class="filter-option">
                <input type="radio" name="price" value="5-10">
                <span class="checkmark"></span>
                5 - 10 triệu
              </label>
              <label class="filter-option">
                <input type="radio" name="price" value="10-20">
                <span class="checkmark"></span>
                10 - 20 triệu
              </label>
              <label class="filter-option">
                <input type="radio" name="price" value="20-50">
                <span class="checkmark"></span>
                20 - 50 triệu
              </label>
              <label class="filter-option">
                <input type="radio" name="price" value="50+">
                <span class="checkmark"></span>
                Trên 50 triệu
              </label>
            </div>
          </div>

          <!-- Thương hiệu -->
          <div class="filter-group">
            <h4>Thương hiệu</h4>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" name="brand" value="apple">
                <span class="checkmark"></span>
                Apple
              </label>
              <label class="filter-option">
                <input type="checkbox" name="brand" value="samsung">
                <span class="checkmark"></span>
                Samsung
              </label>
              <label class="filter-option">
                <input type="checkbox" name="brand" value="sony">
                <span class="checkmark"></span>
                Sony
              </label>
              <label class="filter-option">
                <input type="checkbox" name="brand" value="asus">
                <span class="checkmark"></span>
                ASUS
              </label>
              <label class="filter-option">
                <input type="checkbox" name="brand" value="dell">
                <span class="checkmark"></span>
                Dell
              </label>
              <label class="filter-option">
                <input type="checkbox" name="brand" value="lenovo">
                <span class="checkmark"></span>
                Lenovo
              </label>
            </div>
          </div>

          <!-- Đánh giá -->
          <div class="filter-group">
            <h4>Đánh giá</h4>
            <div class="filter-options">
              <label class="filter-option rating-option">
                <input type="radio" name="rating" value="5">
                <span class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </span>
                <span class="rating-text">5 sao</span>
              </label>
              <label class="filter-option rating-option">
                <input type="radio" name="rating" value="4">
                <span class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </span>
                <span class="rating-text">4 sao trở lên</span>
              </label>
              <label class="filter-option rating-option">
                <input type="radio" name="rating" value="3">
                <span class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </span>
                <span class="rating-text">3 sao trở lên</span>
              </label>
            </div>
          </div>

          <!-- Tình trạng -->
          <div class="filter-group">
            <h4>Tình trạng</h4>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" name="status" value="in-stock">
                <span class="checkmark"></span>
                Còn hàng
              </label>
              <label class="filter-option">
                <input type="checkbox" name="status" value="pre-order">
                <span class="checkmark"></span>
                Đặt trước
              </label>
              <label class="filter-option">
                <input type="checkbox" name="status" value="sale">
                <span class="checkmark"></span>
                Đang giảm giá
              </label>
            </div>
          </div>
        </div>


        <button class="apply-filters-btn" id="applyFilters">
          Áp dụng bộ lọc
        </button>
      </aside>

      <!-- Main Content -->
      <main class="products-main">
        <!-- Toolbar -->
        <div class="products-toolbar">
          <div class="toolbar-left">
            <div class="view-mode">
              <button class="view-btn active" data-view="grid">
                <i class="fas fa-th"></i>
              </button>
              <button class="view-btn" data-view="list">
                <i class="fas fa-list"></i>
              </button>
            </div>
            <div class="results-count">
              Hiển thị <span id=""><?php echo count($productsData)?></span> sản phẩm
            </div>
          </div>

          <div class="toolbar-right">
            <div class="sort-by">
              <label>Sắp xếp:</label>
              <select id="sortBy">
                <option value="default">Mặc định</option>
                <option value="price-asc">Giá: Thấp đến Cao</option>
                <option value="price-desc">Giá: Cao đến Thấp</option>
                <option value="newest">Mới nhất</option>
                <option value="popular">Bán chạy</option>
                <option value="rating">Đánh giá cao</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="productsGrid">
          <!-- Sản phẩm sẽ được thêm bằng JavaScript -->
          <?php foreach ($productsData as $product_ec): ?>
            <div class="product-card">
              <div class="product-tags  ">
                <p class="product-tag <?php echo 'tag-' . $product_ec['stock_status']?>">
                  <?php echo $product_ec['stock_status']?>
                </p>
              </div>
              <div class="product-actions">
                <button class="action-btn wishlist-btn" data-id="<?php echo $product_ec['id']?>">
                  <i class="far fa-heart"></i>
                </button>
                <button class="action-btn compare-btn" data-id="<?php echo $product_ec['id']?>">
                  <i class="fas fa-chart-bar"></i>
                </button>
              </div>
              <div class="product-img">
                <img src="<?php echo $product_ec['image']?>" alt="<?php echo $product_ec['name']?>">
              </div>
              <div class="product-info">
                <div class="product-category"><?php echo $product_ec['category']?></div>
                <h3 class="product-name"><?php echo $product_ec['name']?></h3>
                <div class="product-price">
                  <span class="current-price"><?php echo $product_ec['current_price']?></span>
                  <span class="original-price"><?php echo $product_ec['original_price']?></span>
                </div>
                <div class="product-rating">
                  <div class="stars">
                    <?php echo $product_ec['rating']?>
                  </div>
                  <span class="rating-count">(<?php echo $product_ec['reviews']?>)</span>
                </div>
                <!-- <button class="add-to-cart" data-id="${product.id}">
                  <i class="fas fa-shopping-cart"></i>
                  Thêm vào giỏ
                </button> -->
                <a href="product_detail.php?id=<?php echo $product_ec['id']; ?>" class="add-to-cart">
                  <i class="fas fa-eye"></i>
                  Xem thêm
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
          <button class="page-btn prev" disabled>
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="page-btn active">1</button>
          <button class="page-btn">2</button>
          <button class="page-btn">3</button>
          <button class="page-btn">4</button>
          <button class="page-btn next">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>

        <!-- Related Products -->
        <section class="related-products">
          <h3>Sản phẩm liên quan</h3>
          <div class="related-grid" id="relatedProducts">
            <!-- Sản phẩm liên quan sẽ được thêm bằng JavaScript -->
          </div>
        </section>
      </main>
    </div>
  </div>
</div>

<!-- Quick View Modal -->
<div class="modal quick-view-modal" id="quickViewModal">
  <div class="modal-content">
    <button class="modal-close" id="closeQuickView">
      <i class="fas fa-times"></i>
    </button>
    <div class="quick-view-content" id="quickViewContent">
      <!-- Nội dung quick view sẽ được thêm bằng JavaScript -->
    </div>
  </div>
</div>

<!-- Floating Filter Button (Mobile) -->
<button class="floating-filter-btn" id="floatingFilterBtn">
  <i class="fas fa-filter"></i>
  <span>Bộ lọc</span>
</button>

<?php include 'footer.php'?>

<!--<script>-->
<!--  document.addEventListener('DOMContentLoaded', function() {-->
<!--    // Dữ liệu sản phẩm mẫu-->
<!--    const products = [-->
<!---->
<!--    ];-->
<!---->
<!--    // Hiển thị sản phẩm-->
<!--    function displayProducts(productsToShow) {-->
<!--      const productsGrid = document.getElementById('productsGrid');-->
<!--      productsGrid.innerHTML = '';-->
<!---->
<!--      productsToShow.forEach(product => {-->
<!--        productsGrid.appendChild(productCard);-->
<!--      });-->
<!---->
<!--    }-->
<!---->
<!--    // Tạo card sản phẩm-->
<!--    function createProductCard(product) {-->
<!--      const card = document.createElement('div');-->
<!--      card.className = 'product-card';-->
<!--      card.innerHTML = `-->
<!--            <div class="product-tags">-->
<!--                ${product.tags.map(tag => `-->
<!--                    <span class="product-tag tag-${tag}">${tag === 'new' ? 'Mới' : tag === 'sale' ? 'Sale' : 'Giới hạn'}</span>-->
<!--                `).join('')}-->
<!--            </div>-->
<!--            <div class="product-actions">-->
<!--                <button class="action-btn wishlist-btn" data-id="${product.id}">-->
<!--                    <i class="far fa-heart"></i>-->
<!--                </button>-->
<!--                <button class="action-btn compare-btn" data-id="${product.id}">-->
<!--                    <i class="fas fa-chart-bar"></i>-->
<!--                </button>-->
<!--            </div>-->
<!--            <div class="product-img">-->
<!--                <img src="${product.image}" alt="${product.name}">-->
<!--            </div>-->
<!--            <div class="product-info">-->
<!--                <div class="product-category">${getCategoryName(product.category)}</div>-->
<!--                <h3 class="product-name">${product.name}</h3>-->
<!--                <p class="product-description">${product.description}</p>-->
<!--                <div class="product-price">-->
<!--                    <span class="current-price">${formatPrice(product.price)}</span>-->
<!--                    ${product.originalPrice > product.price ?-->
<!--        `<span class="original-price">${formatPrice(product.originalPrice)}</span>` : ''-->
<!--      }-->
<!--                </div>-->
<!--                <div class="product-rating">-->
<!--                    <div class="stars">-->
<!--                        ${generateStars(product.rating)}-->
<!--                    </div>-->
<!--                    <span class="rating-count">(${product.reviews})</span>-->
<!--                </div>-->
<!--                <button class="add-to-cart" data-id="${product.id}">-->
<!--                    <i class="fas fa-shopping-cart"></i>-->
<!--                    Thêm vào giỏ-->
<!--                </button>-->
<!--                <a href="product_detail.php" class="quick-view-btn"">-->
<!--                    <i class="fas fa-eye"></i>-->
<!--                    Xem thêm-->
<!--                </a>-->
<!--            </div>-->
<!--        `;-->
<!--      return card;-->
<!--    }-->
<!---->
<!--    // Hàm hỗ trợ-->
<!--    function getCategoryName(category) {-->
<!--      const categories = {-->
<!--        'laptop': 'Laptop & Máy tính',-->
<!--        'smartphone': 'Điện thoại',-->
<!--        'tablet': 'Máy tính bảng',-->
<!--        'audio': 'Tai nghe & Loa',-->
<!--        'accessory': 'Phụ kiện'-->
<!--      };-->
<!--      return categories[category] || category;-->
<!--    }-->
<!---->
<!--    function formatPrice(price) {-->
<!--      return new Intl.NumberFormat('vi-VN', {-->
<!--        style: 'currency',-->
<!--        currency: 'VND'-->
<!--      }).format(price);-->
<!--    }-->
<!---->
<!--    function generateStars(rating) {-->
<!--      let stars = '';-->
<!--      const fullStars = Math.floor(rating);-->
<!--      const halfStar = rating % 1 >= 0.5;-->
<!---->
<!--      for (let i = 0; i < fullStars; i++) {-->
<!--        stars += '<i class="fas fa-star"></i>';-->
<!--      }-->
<!---->
<!--      if (halfStar) {-->
<!--        stars += '<i class="fas fa-star-half-alt"></i>';-->
<!--      }-->
<!---->
<!--      const emptyStars = 5 - Math.ceil(rating);-->
<!--      for (let i = 0; i < emptyStars; i++) {-->
<!--        stars += '<i class="far fa-star"></i>';-->
<!--      }-->
<!---->
<!--      return stars;-->
<!--    }-->
<!---->
<!--    // View mode toggle-->
<!--    const viewBtns = document.querySelectorAll('.view-btn');-->
<!--    const productsGrid = document.getElementById('productsGrid');-->
<!---->
<!--    viewBtns.forEach(btn => {-->
<!--      btn.addEventListener('click', function() {-->
<!--        viewBtns.forEach(b => b.classList.remove('active'));-->
<!--        this.classList.add('active');-->
<!---->
<!--        const viewMode = this.dataset.view;-->
<!--        const productCards = document.querySelectorAll('.product-card');-->
<!---->
<!--        productCards.forEach(card => {-->
<!--          card.classList.toggle('list-view', viewMode === 'list');-->
<!--        });-->
<!---->
<!--        productsGrid.classList.toggle('list-view', viewMode === 'list');-->
<!--      });-->
<!--    });-->
<!---->
<!--    // Quick view modal-->
<!--    const quickViewModal = document.getElementById('quickViewModal');-->
<!--    const quickViewContent = document.getElementById('quickViewContent');-->
<!--    const closeQuickView = document.getElementById('closeQuickView');-->
<!---->
<!--    document.addEventListener('click', function(e) {-->
<!--      if (e.target.classList.contains('quick-view-btn')) {-->
<!--        const productId = parseInt(e.target.dataset.id);-->
<!--        const product = products.find(p => p.id === productId);-->
<!--        showQuickView(product);-->
<!--      }-->
<!--    });-->
<!---->
<!--    function showQuickView(product) {-->
<!--      quickViewContent.innerHTML = `-->
<!--            <div class="quick-view-grid">-->
<!--                <div class="quick-view-image">-->
<!--                    <img src="${product.image}" alt="${product.name}">-->
<!--                </div>-->
<!--                <div class="quick-view-info">-->
<!--                    <div class="product-tags">-->
<!--                        ${product.tags.map(tag => `-->
<!--                            <span class="product-tag tag-${tag}">${tag === 'new' ? 'Mới' : tag === 'sale' ? 'Sale' : 'Giới hạn'}</span>-->
<!--                        `).join('')}-->
<!--                    </div>-->
<!--                    <h2>${product.name}</h2>-->
<!--                    <div class="product-rating large">-->
<!--                        <div class="stars">-->
<!--                            ${generateStars(product.rating)}-->
<!--                        </div>-->
<!--                        <span class="rating-count">${product.reviews} đánh giá</span>-->
<!--                    </div>-->
<!--                    <div class="product-price large">-->
<!--                        <span class="current-price">${formatPrice(product.price)}</span>-->
<!--                        ${product.originalPrice > product.price ?-->
<!--        `<span class="original-price">${formatPrice(product.originalPrice)}</span>` : ''-->
<!--      }-->
<!--                    </div>-->
<!--                    <p class="product-description">${product.description}</p>-->
<!--                    <div class="quick-view-actions">-->
<!--                        <button class="add-to-cart large" data-id="${product.id}">-->
<!--                            <i class="fas fa-shopping-cart"></i>-->
<!--                            Thêm vào giỏ hàng-->
<!--                        </button>-->
<!--                        <button class="wishlist-btn large" data-id="${product.id}">-->
<!--                            <i class="far fa-heart"></i>-->
<!--                            Yêu thích-->
<!--                        </button>-->
<!--                    </div>-->
<!--                    <div class="product-specs">-->
<!--                        <h4>Thông số kỹ thuật</h4>-->
<!--                        <ul>-->
<!--                            <li><strong>Thương hiệu:</strong> ${product.brand.toUpperCase()}</li>-->
<!--                            <li><strong>Danh mục:</strong> ${getCategoryName(product.category)}</li>-->
<!--                            <li><strong>Tình trạng:</strong> ${product.inStock ? 'Còn hàng' : 'Hết hàng'}</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        `;-->
<!--      quickViewModal.classList.add('show');-->
<!--    }-->
<!---->
<!--    closeQuickView.addEventListener('click', function() {-->
<!--      quickViewModal.classList.remove('show');-->
<!--    });-->
<!---->
<!--    quickViewModal.addEventListener('click', function(e) {-->
<!--      if (e.target === quickViewModal) {-->
<!--        quickViewModal.classList.remove('show');-->
<!--      }-->
<!--    });-->
<!---->
<!--    // Floating filter button-->
<!--    const floatingFilterBtn = document.getElementById('floatingFilterBtn');-->
<!--    const filterSidebar = document.querySelector('.filter-sidebar');-->
<!--    //-->
<!--    // floatingFilterBtn.addEventListener('click', function() {-->
<!--    //   filterSidebar.style.display = filterSidebar.style.display === 'block' ? 'none' : 'block';-->
<!--    // });-->
<!---->
<!--    floatingFilterBtn.addEventListener('click', function() {-->
<!--      filterSidebar.classList.toggle('show');-->
<!--    });-->
<!---->
<!--// Đóng filter khi click bên ngoài trên mobile-->
<!--    document.addEventListener('click', function(e) {-->
<!--      if (window.innerWidth <= 768) {-->
<!--        if (!filterSidebar.contains(e.target) && !floatingFilterBtn.contains(e.target)) {-->
<!--          filterSidebar.classList.remove('show');-->
<!--        }-->
<!--      }-->
<!--    });-->
<!---->
<!--    // Clear filters-->
<!--    document.getElementById('clearFilters').addEventListener('click', function() {-->
<!--      const inputs = document.querySelectorAll('.filter-sidebar input');-->
<!--      inputs.forEach(input => {-->
<!--        input.checked = false;-->
<!--      });-->
<!--    });-->
<!---->
<!--    // Apply filters-->
<!--    document.getElementById('applyFilters').addEventListener('click', function() {-->
<!--      // Logic lọc sản phẩm sẽ được thêm ở đây-->
<!--      displayProducts(products); // Tạm thời hiển thị tất cả-->
<!--    });-->
<!---->
<!--    // Sort products-->
<!--    document.getElementById('sortBy').addEventListener('change', function(e) {-->
<!--      const sortValue = e.target.value;-->
<!--      let sortedProducts = [...products];-->
<!---->
<!--      switch(sortValue) {-->
<!--        case 'price-asc':-->
<!--          sortedProducts.sort((a, b) => a.price - b.price);-->
<!--          break;-->
<!--        case 'price-desc':-->
<!--          sortedProducts.sort((a, b) => b.price - a.price);-->
<!--          break;-->
<!--        case 'newest':-->
<!--          // Giả sử sản phẩm mới hơn có ID lớn hơn-->
<!--          sortedProducts.sort((a, b) => b.id - a.id);-->
<!--          break;-->
<!--        case 'popular':-->
<!--          sortedProducts.sort((a, b) => b.reviews - a.reviews);-->
<!--          break;-->
<!--        case 'rating':-->
<!--          sortedProducts.sort((a, b) => b.rating - a.rating);-->
<!--          break;-->
<!--      }-->
<!---->
<!--      displayProducts(sortedProducts);-->
<!--    });-->
<!---->
<!--    // Hiển thị sản phẩm ban đầu-->
<!--    displayProducts(products);-->
<!---->
<!--    // Hiển thị sản phẩm liên quan-->
<!--    const relatedProducts = document.getElementById('relatedProducts');-->
<!--    const related = products.slice(0, 4); // Lấy 4 sản phẩm đầu làm liên quan-->
<!---->
<!--    related.forEach(product => {-->
<!--      relatedCard.classList.add('related-card');-->
<!--      relatedProducts.appendChild(relatedCard);-->
<!--    });-->
<!--  });-->
<!--</script>-->

</body>
</html>
