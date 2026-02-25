<script>
(function(){
  'use strict';

  const sidebar   = document.getElementById('sidebar');
  const mainWrap  = document.getElementById('mainWrap');
  const overlay   = document.getElementById('sbOverlay');
  const toggleBtn = document.getElementById('sidebarToggle');
  const icon      = document.getElementById('toggleIcon');
  const BP        = 992;

  const isMobile = () => window.innerWidth < BP;

  let deskCollapsed = localStorage.getItem('sb_col') === '1';

  function setDesktop(collapsed, animate){
    if(!animate){
      sidebar.style.transition  = 'none';
      mainWrap.style.transition = 'none';
      sidebar.offsetHeight;
    }
    sidebar.classList.toggle('collapsed', collapsed);
    mainWrap.classList.toggle('collapsed', collapsed);
    icon.className = collapsed ? 'fas fa-indent' : 'fas fa-outdent';
    if(!animate){
      sidebar.offsetHeight;
      sidebar.style.transition  = '';
      mainWrap.style.transition = '';
    }
  }

  function openDrawer(){
    sidebar.classList.add('open');
    overlay.classList.add('show');
    icon.className = 'fas fa-xmark';
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer(){
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    icon.className = 'fas fa-bars';
    document.body.style.overflow = '';
  }

  toggleBtn.addEventListener('click', () => {
    if(isMobile()){
      sidebar.classList.contains('open') ? closeDrawer() : openDrawer();
    } else {
      deskCollapsed = !deskCollapsed;
      localStorage.setItem('sb_col', deskCollapsed ? '1' : '0');
      setDesktop(deskCollapsed, true);
    }
  });

  overlay.addEventListener('click', closeDrawer);

  sidebar.querySelectorAll('.sb-link').forEach(el =>
    el.addEventListener('click', () => { if(isMobile()) closeDrawer(); })
  );

  let rt;
  window.addEventListener('resize', () => {
    clearTimeout(rt);
    rt = setTimeout(() => {
      if(isMobile()){
        sidebar.classList.remove('collapsed');
        mainWrap.classList.remove('collapsed');
        icon.className = sidebar.classList.contains('open') ? 'fas fa-xmark' : 'fas fa-bars';
        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
      } else {
        closeDrawer();
        setDesktop(deskCollapsed, false);
      }
    }, 80);
  });

  if(isMobile()){
    icon.className = 'fas fa-bars';
  } else {
    setDesktop(deskCollapsed, false);
  }

})();

/* ── AJAX setup ── */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json'
    }
});

/* ── Flash messages ── */
@if(session('success'))
  Swal.fire({ 
    icon:'success', 
    title:'Success', 
    text:@json(session('success')), 
    showConfirmButton:true, 
    confirmButtonText:'OK', 
    timerProgressBar:false, 
    toast:true, 
    position:'top-end' 
  });
@endif
@if(session('error'))
  Swal.fire({ 
    icon:'error', 
    title:'Error', 
    text:@json(session('error')), 
    showConfirmButton:true, 
    confirmButtonText:'OK', 
    toast:true, 
    position:'top-end' 
  });
@endif

/* ── Delete helper ── */
function confirmDelete(url, msg = 'This action cannot be undone.'){
  Swal.fire({
    title:'Delete this item?', 
    text:msg, 
    icon:'warning',
    showCancelButton:true,
    confirmButtonColor:'#ff4d6d', 
    cancelButtonColor:'#6b7399',
    confirmButtonText:'Yes, delete', 
    cancelButtonText:'Cancel'
  }).then(r => {
    if(r.isConfirmed){
      $.ajax({
        url,
        type: 'POST',
        data: { _method: 'DELETE' },
        success: res => {
          if(res.success){
            Swal.fire({
              icon:'success',
              title:'Deleted',
              text:res.message,
              showConfirmButton:true,
              confirmButtonText:'OK',
              confirmButtonColor:'#4f63d2'
            }).then(() => location.reload());
          }
        },
        error: () => Swal.fire('Error','Something went wrong.','error')
      });
    }
  });
}
</script>