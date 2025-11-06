<?php
// dynamic_header.php
// Include this file in your PHP pages where you want the draggable header.
// Example: <?php include 'dynamic_header.php';
?>

<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dynamic Draggable Header</title>
<!--  <link rel="stylesheet" hre>-->
</head>
<body>

<!-- Header component -->
<header id="dynamicHeader" class="dynamic-header" role="banner" aria-label="Thanh điều hướng động">
  <div class="dh__logo">
    <div class="dot">HB</div>
    <div>
      <div class="dh__title">Tech Shop</div>
    </div>
  </div>

  <div class="dh__spacer"></div>

  <nav class="dh__controls" aria-label="header controls">
    <ul class="dh__menu">
      <li>
        <a href="index.php" data-tooltip="Trang chủ" class="dh__inmenu">
          <i class="fa-solid fa-house"></i>
          <span class="hv_left_right">Trang chủ</span>
        </a>
      </li>
      <li>
        <a href="about.php" data-tooltip="Sản phẩm" class="dh__inmenu">
          <i class="fa-solid fa-shop"></i>
          <span class="hv_left_right">Sản phẩm</span>
        </a>
      </li>
      <li>
        <a href="products.php" data-tooltip="Giới thiệu" class="dh__inmenu">
          <i class="fa-solid fa-address-card"></i>
          <span class="hv_left_right">Giới thiệu</span>
        </a>
      </li>
      <li>
        <a href="contact.php" data-tooltip="Liên hệ" class="dh__inmenu">
          <i class="fa-solid fa-headset"></i>
          <span class="hv_left_right">Liên hệ</span>
        </a>
      </li>

      <li>
        <a href="contact.php" data-tooltip="abc">
          <i class="fa-solid fa-user"></i>
        </a>
      </li>

      <li>
        <a href="contact.php" data-tooltip="Cài đặt">
          <i class="fa-solid fa-gear"></i>
        </a>
      </li>
    </ul>
  </nav>

  <div id="dhHandle" class="dh__handle" title="Kéo để di chuyển">
    <i class="fa-solid fa-bars"></i>
  </div>
</header>

<script>
  (function(){
    const header = document.getElementById('dynamicHeader');
    const handle = document.getElementById('dhHandle');
    const toggleCorner = document.getElementById('toggleCorner');
    let dragging = false;
    let startX=0, startY=0, origX=0, origY=0;

    // Load saved state (dock edge & offset)
    const STATE_KEY = 'dynamicHeaderState_v1';
    function saveState(state){ localStorage.setItem(STATE_KEY, JSON.stringify(state)); }
    function loadState(){ try{ return JSON.parse(localStorage.getItem(STATE_KEY) || 'null'); }catch(e){return null} }

    // Apply dock class and position
    function applyState(state){
      header.classList.remove('dock-top','dock-bottom','dock-left','dock-right');
      header.style.left = '';
      header.style.top = '';
      header.style.right = '';
      header.style.bottom = '';
      header.style.transform = '';

      if(!state) return;
      const edge = state.edge;
      const pos = state.pos ?? 0.5;
      if(edge === 'top'){
        header.classList.add('dock-top');
        header.style.left = (pos*100) + '%';
        header.style.transform = 'translateX(-50%)';
        header.style.top = '12px';
        header.style.bottom = 'auto';
      } else if(edge === 'bottom'){
        header.classList.add('dock-bottom');
        header.style.left = (pos*100) + '%';
        header.style.transform = 'translateX(-50%)';
        header.style.bottom = '12px';
        header.style.top = 'auto';
      } else if(edge === 'left'){
        header.classList.add('dock-left');
        header.style.top = (pos*100) + '%';
        header.style.transform = 'translateY(-50%)';
        header.style.left = '12px';
      } else if(edge === 'right'){
        header.classList.add('dock-right');
        header.style.top = (pos*100) + '%';
        header.style.transform = 'translateY(-50%)';
        header.style.right = '12px';
        header.style.left = 'auto';
      }
    }

    // initial
    const initial = loadState();
    if(initial) applyState(initial);

    // helper to get bounding center normalized
    function getNormalizedCenter(x,y){
      const vw = window.innerWidth;
      const vh = window.innerHeight;
      return {x: x / vw, y: y / vh};
    }

    function onPointerDown(e){
      dragging = true;
      header.classList.add('dragging');
      startX = e.clientX; startY = e.clientY;
      const rect = header.getBoundingClientRect();
      origX = rect.left; origY = rect.top;
      document.addEventListener('pointermove', onPointerMove);
      document.addEventListener('pointerup', onPointerUp);
    }

    function onPointerMove(e){
      if(!dragging) return;
      const dx = e.clientX - startX;
      const dy = e.clientY - startY;
      header.style.left = (origX + dx) + 'px';
      header.style.top = (origY + dy) + 'px';
      header.style.right = 'auto'; header.style.bottom = 'auto';
      header.style.transform = 'none';
    }

    function onPointerUp(e){
      dragging = false;
      header.classList.remove('dragging');
      document.removeEventListener('pointermove', onPointerMove);
      document.removeEventListener('pointerup', onPointerUp);

      // determine nearest edge by center point
      const rect = header.getBoundingClientRect();
      const cx = rect.left + rect.width/2;
      const cy = rect.top + rect.height/2;

      const {x: nx, y: ny} = getNormalizedCenter(cx, cy);
      // distances to edges (normalized)
      const toTop = ny;
      const toBottom = 1 - ny;
      const toLeft = nx;
      const toRight = 1 - nx;
      const min = Math.min(toTop,toBottom,toLeft,toRight);

      let edge = 'top';
      if(min === toTop) edge = 'top';
      else if(min === toBottom) edge = 'bottom';
      else if(min === toLeft) edge = 'left';
      else if(min === toRight) edge = 'right';

      // compute position along edge (0..1)
      let pos = 0.5;
      if(edge === 'top' || edge === 'bottom'){
        const vw = window.innerWidth;
        pos = Math.min(0.95, Math.max(0.05, (cx / vw)));
      } else {
        const vh = window.innerHeight;
        pos = Math.min(0.95, Math.max(0.05, (cy / vh)));
      }

      const newState = {edge, pos};
      applyState(newState);
      saveState(newState);
    }

    // Attach pointerdown to header handle and header itself
    handle.addEventListener('pointerdown', onPointerDown);
    header.addEventListener('pointerdown', function(e){
      // nếu nhấn vào nút bên trong thì không bắt drag
      if(e.target.closest('button') || e.target.closest('a')) return;
      onPointerDown(e);
    });

    // Toggle border-radius example
    toggleCorner.addEventListener('click', ()=>{
      const cur = getComputedStyle(header).borderRadius;
      if(cur.includes('18')){
        header.style.borderRadius = '6px';
      } else {
        header.style.borderRadius = '';
      }
    });

    // keyboard: press D to cycle docks (accessible)
    window.addEventListener('keydown', (e)=>{
      if(e.key.toLowerCase()==='d'){
        const order = ['top','right','bottom','left'];
        const st = loadState() || {edge:'top',pos:0.5};
        const idx = order.indexOf(st.edge || 'top');
        const next = order[(idx+1)%order.length];
        const ns = {edge: next, pos: 0.5};
        applyState(ns); saveState(ns);
      }
    });

    // ensure header stays visible on resize
    window.addEventListener('resize', ()=>{
      const st = loadState();
      if(st) applyState(st);
    });

  })();
</script>

</body>
</html>
