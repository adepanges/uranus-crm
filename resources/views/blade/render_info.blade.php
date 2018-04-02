@if(ENVIRONMENT == 'development')
        <script type="text/javascript">
            console.log('CodeIgniter Version <?php echo CI_VERSION ?>')
            console.log('This page took {{ (microtime(true) - URANUS_LAUNCH) }} seconds to render')
        </script>
@endif
