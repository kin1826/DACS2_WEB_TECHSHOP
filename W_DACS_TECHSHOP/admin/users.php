<?php
global $userModel;
$users = $userModel->getAll(50, 0);
?>

<div class="data-table">
  <table>
    <thead>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Level</th>
      <th>Ngày tạo</th>
      <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($users)): ?>
      <tr>
        <td colspan="7" class="empty-state">
          <i class="fas fa-users-slash"></i>
          <h3>Không có người dùng nào</h3>
          <p>Chưa có người dùng nào trong hệ thống</p>
        </td>
      </tr>
    <?php else: ?>
      <?php foreach ($users as $user):
        // Map level to badge class
        $levelClass = 'user';
        if ($user['level_u'] === 'admin') $levelClass = 'admin';
        if ($user['level_u'] === 'moderator') $levelClass = 'moderator';
        ?>
        <tr>
          <td><?php echo $user['id']; ?></td>
          <td>
            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
          </td>
          <td>
            <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="text-primary">
              <?php echo htmlspecialchars($user['email']); ?>
            </a>
          </td>
          <td>
          <span class="phone-cell">
            <?php echo htmlspecialchars(isset($user['phone']) ? $user['phone'] : 'N/A'); ?>
          </span>
          </td>
          <td>
          <span class="level-badge <?php echo $levelClass; ?>" data-level="<?php echo htmlspecialchars($user['level_u']); ?>">
            <i class="fas fa-user-<?php echo $levelClass === 'admin' ? 'shield' : ($levelClass === 'moderator' ? 'cog' : 'circle'); ?>"></i>
            <?php echo htmlspecialchars($user['level_u']); ?>
          </span>
          </td>
          <td>
          <span class="date-cell">
            <?php
            echo '<p>' . date('d/m/Y', strtotime($user['created_at'])) . '</p>';
            echo '<br><small class="text-muted">' . date('H:i:s', strtotime($user['created_at'])) . '</small>';
            ?>
          </span>
          </td>
          <td>
            <div class="action-buttons">
              <a href="admin.php?page=users&action=edit&id=<?php echo $user['id']; ?>"
                 class="btn btn-edit" title="Chỉnh sửa">
                <i class="fas fa-edit"></i>
              </a>
              <a href="admin.php?page=users&action=delete_user&id=<?php echo $user['id']; ?>"
                 class="btn btn-danger"
                 onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')"
                 title="Xóa người dùng">
                <i class="fas fa-trash"></i>
              </a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<style>
  .data-table {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e4e6ef;
    margin-top: 25px;
  }

  .data-table table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 800px;
  }

  .data-table thead {
    background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
  }

  .data-table th {
    padding: 20px 25px;
    text-align: left;
    font-weight: 600;
    color: #ffffff;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    border-bottom: 2px solid #e4e6ef;
    position: relative;
  }

  .data-table th::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
  }

  .data-table th:hover::after {
    width: 100%;
  }

  .data-table td {
    padding: 18px 25px;
    border-bottom: 1px solid #f0f4f8;
    vertical-align: middle;
    color: #5e6278;
    font-size: 14px;
  }

  .data-table tbody tr {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .data-table tbody tr:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  }

  .data-table tbody tr:last-child td {
    border-bottom: none;
  }

  /* User info styling */
  .data-table td:nth-child(2) { /* Username column */
    font-weight: 600;
    color: #2c3e50;
  }

  .data-table td:nth-child(3) { /* Email column */
    color: #667eea;
    font-weight: 500;
  }

  .data-table td:nth-child(4) { /* Phone column */
    font-family: 'Consolas', monospace;
    background: #f8f9fa;
    padding: 6px 12px;
    border-radius: 8px;
    display: inline-block;
  }

  .data-table td:nth-child(5) { /* Level column */
    position: relative;
    padding-left: 30px;
  }

  .data-table td:nth-child(5)::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #10b981;
  }

  .data-table td:nth-child(5)[data-level="admin"]::before {
    background: #ef4444;
  }

  .data-table td:nth-child(5)[data-level="moderator"]::before {
    background: #f59e0b;
  }

  .data-table td:nth-child(5)[data-level="user"]::before {
    background: #10b981;
  }

  .data-table td:nth-child(6) { /* Created at column */
    color: #7f8c8d;
    font-size: 13px;
    white-space: nowrap;
  }

  /* Action buttons */
  .data-table .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
    font-size: 14px;
    margin: 0 4px;
  }

  .data-table .btn-edit {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #667eea;
    border: 1px solid rgba(102, 126, 234, 0.2);
  }

  .data-table .btn-edit:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .data-table .btn-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 138, 101, 0.1) 100%);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
  }

  .data-table .btn-danger:hover {
    background: linear-gradient(135deg, #ef4444 0%, #ff8a65 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
  }

  /* Empty state */
  .data-table .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #a1a5b7;
  }

  .data-table .empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
  }

  .data-table .empty-state h3 {
    margin: 0 0 10px 0;
    color: #5e6278;
    font-size: 24px;
    font-weight: 600;
  }

  .data-table .empty-state p {
    max-width: 400px;
    margin: 0 auto 20px;
    font-size: 16px;
    line-height: 1.6;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .data-table {
      border-radius: 12px;
      margin: 15px;
      overflow-x: auto;
    }

    .data-table table {
      min-width: 700px;
    }

    .data-table th,
    .data-table td {
      padding: 15px 20px;
      font-size: 13px;
    }

    .data-table .btn {
      width: 36px;
      height: 36px;
      font-size: 13px;
      margin: 2px;
    }

    .data-table tbody tr:hover {
      transform: none;
    }
  }

  /* Animation for table rows */
  @keyframes fadeInRow {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .data-table tbody tr {
    animation: fadeInRow 0.4s ease forwards;
  }

  .data-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
  .data-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
  .data-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
  .data-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
  .data-table tbody tr:nth-child(5) { animation-delay: 0.5s; }
  .data-table tbody tr:nth-child(6) { animation-delay: 0.6s; }
  .data-table tbody tr:nth-child(7) { animation-delay: 0.7s; }
  .data-table tbody tr:nth-child(8) { animation-delay: 0.8s; }
  .data-table tbody tr:nth-child(9) { animation-delay: 0.9s; }
  .data-table tbody tr:nth-child(10) { animation-delay: 1.0s; }

  /* Level badges styling */
  .level-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .level-badge.admin {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 138, 101, 0.1) 100%);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
  }

  .level-badge.moderator {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.1) 100%);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.2);
  }

  .level-badge.user {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(34, 197, 94, 0.1) 100%);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
  }

  /* Date formatting */
  .date-cell {
    position: relative;
    padding-left: 30px;
  }

  /*.date-cell::before {*/
  /*  content: '📅';*/
  /*  position: absolute;*/
  /*  left: 0;*/
  /*  top: 50%;*/
  /*  transform: translateY(-50%);*/
  /*  font-size: 14px;*/
  /*  opacity: 0.6;*/
  /*}*/

  /* Tooltip for actions */
  .btn[title]:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #2c3e50;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    margin-bottom: 8px;
    z-index: 100;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .btn[title]::after {
    display: none;
  }

  .btn[title]:hover::after {
    display: block;
  }
</style>
