

    <script>
        // Prevent form submission on Enter key for all input fields
        document.addEventListener('keydown', function(event) {
            // Check if the pressed key is Enter and the target is an input (not textarea or button)
            if (
                event.key === 'Enter' &&
                event.target.tagName === 'INPUT' &&
                event.target.type !== 'submit' &&
                event.target.type !== 'button'
            ) {
                event.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>