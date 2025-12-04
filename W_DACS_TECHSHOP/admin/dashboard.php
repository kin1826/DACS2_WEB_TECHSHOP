<?php
// Thống kê tổng quan
require_once 'class/product.php';

$product = new Product();
$totalPro = $product->getTotalProducts();

global $userModel;
$totalUsers = $userModel->count();
// Cần thêm các model khác: Product, Order, Category
?>

<div class="stats-grid">
  <div class="stat-card">
    <h3>Tổng số Users</h3>
    <div class="number"><?php echo $totalUsers; ?></div>
  </div>
  <div class="stat-card">
    <h3>Tổng sản phẩm</h3>
    <div class="number"><?php echo $totalPro; ?></div>
  </div>
  <div class="stat-card">
    <h3>Đơn hàng hôm nay</h3>
    <div class="number">25</div>
  </div>
  <div class="stat-card">
    <h3>Doanh thu tháng</h3>
    <div class="number">50.000.000đ</div>
  </div>
</div>

<style>
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin: 30px 0;
  }

  .stat-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #eef2f7;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    border-color: #dbeafe;
  }

  .stat-card:hover::before {
    opacity: 1;
  }

  .stat-card:nth-child(1):hover {
    border-color: rgba(102, 126, 234, 0.2);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.02), white);
  }

  .stat-card:nth-child(2):hover {
    border-color: rgba(16, 185, 129, 0.2);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.02), white);
  }

  .stat-card:nth-child(3):hover {
    border-color: rgba(245, 158, 11, 0.2);
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.02), white);
  }

  .stat-card:nth-child(4):hover {
    border-color: rgba(239, 68, 68, 0.2);
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.02), white);
  }

  .stat-card h3 {
    margin: 0 0 15px 0;
    font-size: 15px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .stat-card .number {
    font-size: 36px;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 10px;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  }

  .stat-card .trend {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
  }

  .trend-up {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
  }

  .trend-down {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
  }

  .stat-icon {
    position: absolute;
    right: 25px;
    top: 25px;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
  }

  .stat-card:nth-child(1) .stat-icon {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
  }

  .stat-card:nth-child(2) .stat-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
  }

  .stat-card:nth-child(3) .stat-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
  }

  .stat-card:nth-child(4) .stat-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
  }

  .stat-percent {
    position: absolute;
    right: 25px;
    bottom: 25px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  @media (max-width: 768px) {
    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }

    .stat-card {
      padding: 20px;
    }

    .stat-card .number {
      font-size: 28px;
    }

    .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
      top: 20px;
      right: 20px;
    }
  }

  @media (max-width: 480px) {
    .stats-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
