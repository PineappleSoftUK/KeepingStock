$(document).ready(function(){
  /*
  Initialise
  */
  showLoggedOutMenu();
  showHomePage();


  /*
        USER MANAGMENT SECTION
  */

  /*
  User sign up form
  */

  // show sign up / registration form
  $(document).on('click', '#sign_up', function(){

    var html = `
        <h2>Sign Up</h2>
        <form id='sign_up_form'>
          <label for="firstname">Firstname</label>
          <input type="text" class="form-control" name="firstname" id="firstname" required />

          <label for="lastname">Lastname</label>
          <input type="text" class="form-control" name="lastname" id="lastname" required />

          <label for="email">Email</label>
          <input type="email" class="form-control" name="email" id="email" required />

          <label for="password">Password</label>
          <input type="password" class="form-control" name="password" id="password" required />

          <button type='submit'>Sign Up</button>
        </form>
        `;

    $('#content').html(html);
  });

  // trigger when registration form is submitted
  $(document).on('submit', '#sign_up_form', function(){

    // get form data
    var sign_up_form=$(this);
    var form_data=JSON.stringify($(this).serializeObject());

    // submit form data to api
    $.ajax({
      url: apiPath + "api/users/create_user.php",
      type : "POST",
      contentType : 'application/json',
      data : form_data,
      success : function(result) {
        // successful sign up, report response and clear inputs
        responseAlert('success', 'Registration was successful, welcome!');
        sign_up_form.find('input').val('');
      },
      error: function(xhr, resp, text) {
        responseAlert('error', 'Registration failed with the folllowing error: ' + xhr.responseJSON.message);
        console.log(xhr, resp, text);
      }
    });
    return false;
  });

  /*
  User log in form
  */

  // show login form
  $(document).on('click', '#login', function(){
    showLoginPage();
  });

  // show login page
  function showLoginPage(){

    // remove any old jwt cookie
    setCookie("jwt", "", 1);

    // login page html
    var html = `
        <h2>Login</h2>
        <form id='login_form'>
          <label for='email'>Email address</label>
          <input type='email' class='form-control' id='email' name='email' placeholder='Enter email'>

          <label for='password'>Password</label>
          <input type='password' class='form-control' id='password' name='password' placeholder='Password'>

          <button type='submit'>Login</button>
        </form>
        `;

    $('#content').html(html);
    showLoggedOutMenu();
  }

  // function to set cookie
  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  // trigger when login form is submitted
  $(document).on('submit', '#login_form', function(){

    // get form data
    var login_form=$(this);
    var form_data=JSON.stringify(login_form.serializeObject());

    // submit form data to api
    $.ajax({
      url: apiPath + "api/users/login.php",
      type : "POST",
      contentType : 'application/json',
      data : form_data,
      success : function(result){

        // store jwt as cookie
        setCookie("jwt", result.jwt, 1);

        // show home page & alert successful login
        responseAlert('success', 'Login was successful, welcome!');
        showHomePage();
        showLoggedInMenu();
      },
      error: function(xhr, resp, text){
        // alert login failed & empty the input boxes
        responseAlert('error', 'Login failed. Email or password may be incorrect.');
        login_form.find('input').val('');
        console.log(xhr, resp, text);
      }
    });

    return false;
  });

  // if the user is logged in
  function showLoggedInMenu(){
    $("#login, #sign_up").hide();
    $("#logout, #update_account").show();
  }

  // if the user is logged in
  function showLoggedOutMenu(){
    $("#logout, #update_account").hide();
    $("#login, #sign_up").show();

  }

  /*
  Account maintenance
  */
  // show update account form
  $(document).on('click', '#update_account', function(){
    showUpdateAccountForm();
  });

  function showUpdateAccountForm(){
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post(apiPath + "api/users/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
      // if logged in, write and populate the form
      var html = `
              <h2>Update Account</h2>
              <form id='update_account_form'>
                <label for="firstname">Firstname</label>
                <input type="text" class="form-control" name="firstname" id="firstname" required value="` + result.data.firstname + `" />

                <label for="lastname">Lastname</label>
                <input type="text" class="form-control" name="lastname" id="lastname" required value="` + result.data.lastname + `" />

                <input type="hidden" class="form-control" name="email" id="email" required value="` + result.data.email + `" />

                <label for="password">Password</label>
                <input type="password" name="password" id="password" />

                <button type='submit'>Save Changes</button>
              </form>
          `;

      $('#content').html(html);
    })
    // on error/fail, alert user
    .fail(function(result){
      responseAlert('warning', 'Please login to access this page.');
      showLoginPage();
    });
  }

  // trigger when 'update account' form is submitted
  $(document).on('submit', '#update_account_form', function(){
    // get form data
    var update_account_form=$(this);
    var form_data=JSON.stringify(update_account_form.serializeObject());

    // submit form data to api
    $.ajax({
      url: apiPath + "api/users/update_user.php",
      type : "POST",
      contentType : 'application/json',
      //send jwt header
      headers: {
        Authorization: 'Bearer ' + getCookie('jwt')
      },
      data : form_data,
      success : function(result) {

        // tell the user account was updated
        responseAlert('success', 'Account info updated successfully.');

        // store new jwt to coookie
        setCookie("jwt", result.jwt, 1);
      },

      // show error message to user
      error: function(xhr, resp, text){
        console.log(xhr, resp, text);
        if(xhr.responseJSON.message=="Unable to update user."){
          responseAlert('error', 'Unable to update account info');
        }

        else if(xhr.responseJSON.message=="Access denied."){
          responseAlert('warning', 'Please login to access this page.');
          showLoginPage();
        }
      }
    });
    return false;
  });


  /*
  Other user menu items
  */

  // show home page on click of menu item
  $(document).on('click', '#home, #go-home-button', function(){
    showHomePage();
  });

  // logout the user
  $(document).on('click', '#logout', function(){
    // remove any old jwt cookie
    setCookie("jwt", "", 1);

    showLoginPage();
    responseAlert('success', 'You are now logged out.');
  });

  /*
        APP SECTION
  */

  /*
  Home page
  */
  // show home page
  function showHomePage(){
    // get list of products from the API
    $.getJSON(apiPath + "api/product/read.php", function(data){
      // html for listing products
      var read_products_html=`
          <!-- when clicked, it will load the create product form -->
          <button id='create-product-button' class='styledButton createBtn'>
            &plus; Create Product
          </button>
          <!-- start items list -->
          <div id='item-list' class='item-list'>

          `;

          // loop through returned list of data
          $.each(data.records, function(key, val) {

            // creating new div/row per record and assign id
            read_products_html+=`
                <div id='list-item' class='list-item' data-id='` + val.id + `'>
                  <p>
                    <span class='list-item-name'=>` + val.name + `</span>
                    <span class='list-item-category'>&lt` + val.category_name + `&gt</span>
                    <span class='list-item-attributes'>Buy: &pound` + val.price + `</span>
                  </p>
                </div>`;
          });

          // end items list
          read_products_html+=`</div>`;

          // inject to 'page-content' of our app
          $("#content").html(read_products_html);

          // chage page title
          changePageTitle("Home");

    });
  }

  /*
  Create product
  */
  // show html form when 'create product' button was clicked
  $(document).on('click', '#create-product-button', function(){
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post(apiPath + "api/users/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
      $.getJSON(apiPath + "api/category/read.php", function(data){

        // build categories list
        var categories_options_html=`<select name='category_id'>`;
        $.each(data.records, function(key, val){
          categories_options_html+=`<option value='` + val.id + `'>` + val.name + `</option>`;
        });

        categories_options_html+=`</select>`;

        // Build the form
        var create_product_html=`

            <!-- Go back button -->
            <button id='go-home-button' class='styledButton greyBtn'>
              &lt; Go Back
            </button>
            <!-- 'create product' html form -->
            <form id='create-product-form' action='#' method='post' border='0'>

              <label for="create-form-name">Name</label>
              <input type='text' name='name' id="create-form-name" required />

              <label for="create-form-price">Price</label>
              <input type='number' min='1' name='price' id="create-form-price" required />

              <label for="create-form-description">Description</label>
              <input type='text' name='description' id="create-form-description" required />

              <label for="create-form-category">Category</label>
              ` + categories_options_html + `

              <button type='submit'>Save Changes</button>

            </form>`;

        // inject html to 'page-content' of our app
        $("#content").html(create_product_html);

        // chage page title
        changePageTitle("Create Product");

      });
    })
    // show login page on error
    .fail(function(result){
      responseAlert('warning', 'Please login to access this page.');
      showLoginPage();
    });
  });

  // will run if create product form was submitted
  $(document).on('submit', '#create-product-form', function(){
    // get form data
    var form_data=JSON.stringify($(this).serializeObject());

    // submit form data to api
    $.ajax({
      url: apiPath + "api/product/create.php",
      type : "POST",
      contentType : 'application/json',
      //send jwt header
      headers: {
        Authorization: 'Bearer ' + getCookie('jwt')
      },
      data : form_data,
      success : function(result) {
        // product was created, go back to products list
        responseAlert('success', 'Product successfully added');
        showHomePage();
      },
      error: function(xhr, resp, text) {
        // show error to console
        responseAlert('error', 'Create product failed with the folllowing error: ' + xhr.responseJSON.message);
        console.log(xhr, resp, text);
      }
    });
    return false;
  });

  /*
  Product detail/read one
  */
  // handle 'read one' button click
  $(document).on('click', '#list-item', function(){
    var id = $(this).attr('data-id');
    // read product record based on given ID
    $.getJSON(apiPath + "api/product/read_one.php?id=" + id, function(data){
      var read_one_product_html=`
          <!-- Go back button -->
          <button id='go-home-button' class='styledButton greyBtn'>
            &lt; Go Back
          </button>
          <!-- Update button -->
          <button id='update-product-button' class='styledButton greyBtn'  data-id='` + data.id + `'>
            Update Product
          </button>
          <!-- Delete button -->
          <button id='delete-product-button' class='styledButton greyBtn'  data-id='` + data.id + `'>
            Delete Product
          </button>
          <p>Name: ` + data.name + `</p>
          <p>Price: ` + data.price + `</p>
          <p>Description: ` + data.description + `</p>
          <p>Category: ` + data.category_name + `</p>
          `
      // inject html to 'page-content' of our app
      $("#content").html(read_one_product_html);

      // chage page title
      changePageTitle("Product Detail");

    });
  });


  /*
  Update product
  */
  // show html form when 'update product' button was clicked
  $(document).on('click', '#update-product-button', function(){

    // get product id
    var id = $(this).attr('data-id');
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post(apiPath + "api/users/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {


      // read one record based on given product id
      $.getJSON(apiPath + "api/product/read_one.php?id=" + id, function(data){

        // values will be used to fill out our form
        var name = data.name;
        var price = data.price;
        var description = data.description;
        var category_id = data.category_id;
        var category_name = data.category_name;

        $.getJSON(apiPath + "api/category/read.php", function(data){

          // build categories list
          var categories_options_html=`<select name='category_id'>`;
          $.each(data.records, function(key, val){
             //pre-select option is category id is the same
            if(val.id==category_id){ categories_options_html+=`<option value='` + val.id + `' selected>` + val.name + `</option>`; }

            else{ categories_options_html+=`<option value='` + val.id + `'>` + val.name + `</option>`; }
          });

          categories_options_html+=`</select>`;

          // Build the form
          var update_product_html=`

              <!-- Go back button -->
              <button id='go-home-button' class='styledButton greyBtn'>
                &lt; Go Back
              </button>
              <!-- 'update product' html form -->
              <form id='update-product-form' action='#' method='post' border='0'>

                <label for="update-form-name">Name</label>
                <input type='text' name='name' id="update-form-name" value="` + name + `" required />

                <label for="update-form-price">Price</label>
                <input type='number' min='1' name='price' id="update-form-price" value="` + price + `" required />

                <label for="update-form-description">Description</label>
                <input type='text' name='description' id="update-form-description" value="` + description + `"required />

                <label for="update-form-category">Category</label>
                ` + categories_options_html + `

                <!-- hidden 'product id' to identify which record to delete -->
                <input value=\"` + id + `\" name='id' type='hidden' />

                <button type='submit'>Save Changes</button>

              </form>`;

          // inject html to 'page-content' of our app
          $("#content").html(update_product_html);

          // chage page title
          changePageTitle("Update Product");

        });
      });
    })
    // show login page on error
    .fail(function(result){
      responseAlert('warning', 'Please login to access this page.');
      showLoginPage();
    });
  });

  // will run if 'create product' form was submitted
  $(document).on('submit', '#update-product-form', function(){
    // get form data
    var form_data=JSON.stringify($(this).serializeObject());

    // submit form data to api
    $.ajax({
      url: apiPath + "api/product/update.php",
      type : "POST",
      contentType : 'application/json',
      //send jwt header
      headers: {
        Authorization: 'Bearer ' + getCookie('jwt')
      },
      data : form_data,
      success : function(result) {
        // product was created, go back to products list
        responseAlert('success', 'Product successfully updated');
        showHomePage();
      },
      error: function(xhr, resp, text) {
        // show error to console
        responseAlert('error', 'Update product failed with the folllowing error: ' + xhr.responseJSON.message);
        console.log(xhr, resp, text);
      }
    });


      return false;
  });

  /*
  Delete product
  */
  // will run if the delete button was clicked
  $(document).on('click', '#delete-product-button', function(){
    // get product id
    var id = $(this).attr('data-id');
    // validate jwt to verify access
    var jwt = getCookie('jwt');

    $.post(apiPath + "api/users/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
      // Build the page
      var delete_product_html=`
          <p><b>Warning!</b> are you sure you want to delete this?</p>
          <button id='go-home-button' class='styledButton greyBtn'>
            &lt; No, Go Back
          </button>
          <button id='confirm-delete-product-button' data-id='` + id + `' class='styledButton dangerBtn'>
            Yes, Delete Forever
          </button>

          `;
      // inject html to 'page-content' of our app
      $("#content").html(delete_product_html);

      // chage page title
      changePageTitle("Delete Product");
    })
    // show login page on error
    .fail(function(result){
      responseAlert('warning', 'Please login to access this page.');
      showLoginPage();
    });
  });

  // will run if the confirm delete button was clicked
  $(document).on('click', '#confirm-delete-product-button', function(){
    // get product id
    var id = $(this).attr('data-id');
    var form_data = JSON.stringify({ id: id });

    // submit form data to api
    $.ajax({
      url: apiPath + "api/product/delete.php",
      type : "POST",
      contentType : 'application/json',
      //send jwt header
      headers: {
       Authorization: 'Bearer ' + getCookie('jwt')
      },
      data : form_data,
      success : function(result) {
       // product was created, go back to products list
       responseAlert('success', 'Product successfully deleted');
       showHomePage();
      },
      error: function(xhr, resp, text) {
       // show error to console
       responseAlert('error', 'Delete product failed with the folllowing error: ' + xhr.responseJSON.message);
       console.log(xhr, resp, text);
      }
   });
     return false;
  });





});
