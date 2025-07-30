<head>

  <base href="http://localhost/washland/">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">

  <link rel="preload" href="assets/css/custom.css" as="style">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">

  <title>The Washland Portal</title>
</head>


<body class="d-flex">
    <aside id="sidebar" class="d-flex flex-column flex-shrink-0 p-3">
        <a href="index.php" class="navbar-brand text-white mb-4 fs-4">The Washland</a>
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            <li><a href="index.php" class="nav-link text-white">Αρχική</a></li>
            <li><a href="add_card.php" class="nav-link text-white">Νέα κάρτα</a></li>
        </ul>
    </aside>

<!-- Toast -->
  <div class="position-fixed top-0 end-0 p-3" style="z-index:1080;">
    <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>


    <div class="flex-grow-1 d-flex flex-column min-vh-100">
        <main class="flex-grow-1 container-fluid py-4">