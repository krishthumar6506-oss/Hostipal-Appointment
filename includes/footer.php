<?php if (isLoggedIn()): ?>
                </main> <!-- /content-body -->
            </div> <!-- /main-wrapper -->
        <?php endif; ?>
    </div> <!-- /app-container -->

    <!-- jQuery local fallback and CDN fallback -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo base_url("jquery/jquery.min.js"); ?>"><\/script>')</script>
    
    <!-- Main Interactive App JavaScript -->
    <script src="<?php echo base_url('js/main.js'); ?>"></script>
</body>
</html>
