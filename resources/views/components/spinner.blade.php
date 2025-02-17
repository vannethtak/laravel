<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .spinner-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0);
            transition: opacity 1s ease-out;
            z-index: 9999;
        }

        .spinner {
            width: 56px;
            height: 56px;
            display: grid;
            border: 4.5px solid #0000;
            border-radius: 50%;
            border-color: #dbdcef #0000;
            animation: spinner-e04l1k 1s infinite linear;
        }

        .spinner::before,
        .spinner::after {
            content: "";
            grid-area: 1/1;
            margin: 2.2px;
            border: inherit;
            border-radius: 50%;
        }

        .spinner::before {
            border-color: #474bff #0000;
            animation: inherit;
            animation-duration: 0.5s;
            animation-direction: reverse;
        }

        .spinner::after {
            margin: 8.9px;
        }

        @keyframes spinner-e04l1k {
            100% {
                transform: rotate(1turn);
            }
        }

        .hide {
            opacity: 0;
        }
    </style>
</head>
<body>

    <div id="spinner-component"></div>

    <script>
        function showSpinner() {
            const spinnerHTML = `
                <div class="spinner-container" id="spinner-container">
                    <div class="spinner"></div>
                </div>
            `;
            // const spinnerHTML = `
            //     <div class="spinner-container" id="spinner-container">
            //         <div style="font-size: 280px;">🖕🏻</div>
            //     </div>
            // `;
            document.getElementById('spinner-component').innerHTML = spinnerHTML;
        }
        function hideSpinner() {
            setTimeout(() => {
                const spinnerContainer = document.getElementById('spinner-container');
                if (spinnerContainer) {
                    spinnerContainer.classList.add('hide');
                    // document.getElementById('content').style.display = 'block';
                    setTimeout(() => {
                        spinnerContainer.style.display = 'none';
                        document.getElementById('content').style.display = 'block';
                    }, 1000);
                }
            }, 500); // 1-second delay
        }

        showSpinner();

        window.onload = function() {
            hideSpinner();
        };
    </script>
</body>
</html>
