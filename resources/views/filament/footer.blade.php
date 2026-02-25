<footer>
    <style>
        .custom-footer {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 20px 0;
            text-align: center;
            font-family: 'Inter', sans-serif;
        }

        /* Text styling */
        .custom-footer-text {
            font-size: 0.80rem;
            line-height: 1.4;
            font-weight: 500;
            transition: color 0.3s ease;
            width: 100%;
            max-width: 1000px; /* keeps text neat on big screens */
            margin: 0 auto;
        }

        /* Light mode */
        @media (prefers-color-scheme: light) {
            .custom-footer-text {
                color: #1f2937; /* neutral dark */
            }
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            .custom-footer-text {
                color: #e5e7eb; /* light gray */
            }
        }
    </style>

    <div class="custom-footer">
        <div class="custom-footer-text">
            © 2025 Vascular Science LTD. All Rights Reserved | Design & Developed By <strong>Lloyds Knight International</strong>
        </div>
    </div>
</footer>
