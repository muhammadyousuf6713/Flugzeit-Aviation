<!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
@if (auth()->user() || \Request::is('static-sign-up'))
  <footer class="footer py-5">
      <div class="container">
       
              <div class="row">
                  <div class="col-8 mx-auto text-center mt-1">
                      <p class="mb-0 text-secondary">
                          Copyright ©
                          <script>
                              document.write(new Date().getFullYear())
                          </script> Soft by
                          <a style="color: #252f40;" href="https://www.facebook.com/favi247/" class="font-weight-bold ml-1"
                              target="_blank">Flugzeit Aviation</a>.
                      </p>
                  </div>
              </div>
            </div>
        </footer>
        @endif
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
