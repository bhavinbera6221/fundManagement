<?php

use yii\helpers\Url;

if (!isset($this->params['active_menu'])) {
  $this->params['active_menu'] = NULL;
}
?>
<div class="container-fluid page-body-wrapper">
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item <?= $this->params['active_menu'] == 'site' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= Url::to(['site/index']) ?>">
          <i class="icon-grid menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item <?= $this->params['active_menu'] == 'users' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= Url::to(['users/index']) ?>">
          <i class="icon-head menu-icon"></i>
          <span class="menu-title">Users</span>
        </a>
      </li>
      <li class="nav-item <?= $this->params['active_menu'] == 'fund-manage' ? 'active' : '' ?>">
        <a class="nav-link" href="<?//= Url::to(['fund-manage/index']) ?>">
          <i class="icon-bar-graph menu-icon"></i>
          <span class="menu-title">Fund Management</span>
        </a>
      </li>
      <li class="nav-item <?= $this->params['active_menu'] == 'withdrawal' ? 'active' : '' ?>">
        <a class="nav-link" href="<?//= Url::to(['withdrawal/index']) ?>">
          <i class="icon-layout menu-icon"></i>
          <span class="menu-title">Withdrawal Details</span>
        </a>
      </li>
    </ul>
  </nav>