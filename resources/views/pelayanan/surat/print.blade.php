<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            background: white;
        }
    </style>
</head>

<body>

    @include('pelayanan.surat.partials.surat')

    <script>
        window.onload = function() {
            const images = document.images;
            let loaded = 0;
            const total = images.length;

            if (total === 0) {
                window.print();
                return;
            }

            for (let img of images) {
                if (img.complete) {
                    loaded++;
                } else {
                    img.onload = img.onerror = function() {
                        loaded++;
                        if (loaded === total) {
                            window.print();
                        }
                    }
                }
            }

            if (loaded === total) {
                window.print();
            }
        }
    </script>

</body>

</html>
