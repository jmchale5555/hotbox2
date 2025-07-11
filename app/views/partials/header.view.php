<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= ROOT ?>/assets/css/output.css" rel="stylesheet">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

<?php include 'navbar.view.php' ?>
</head>

<body>

    <div class="max-w-screen-lg w-4/5 mx-auto p-2 min-h-full">
