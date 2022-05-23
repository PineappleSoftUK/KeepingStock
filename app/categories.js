$(document).ready(function(){
  /*
  Category Manager
  */

  // show category home page
  $(document).on('click', '#categories_page', function(){
    showCategoryPage();
  });

  function showCategoryPage(){
    // get list of categories from the API
    $.getJSON(apiPath + "api/category/read.php", function(data){
      // html for listing categories
      var read_categories_html=`
          <!-- when clicked, it will load the create category form -->
          <button id='create-category-button' class='styledButton createBtn'>
            &plus; Create Category
          </button>
          <!-- start items list -->
          <div id='item-list' class='item-list'>

          `;

          // loop through returned list of data
          $.each(data.records, function(key, val) {

            // creating new div/row per record and assign id
            read_categories_html+=`
                <div id='list-item' class='list-item' data-id='` + val.id + `'>
                  <p>
                    <span class='list-item-name'=>` + val.name + `</span>
                    <span class='list-item-attributes'>` + val.description + `</span>
                  </p>
                </div>`;
          });

          // end items list
          read_categories_html+=`</div>`;

          // inject to 'page-content' of our app
          $("#content").html(read_categories_html);

          // chage page title
          changePageTitle("Categories");

    });
  }

  /*
  Create category
  */
  // show html form when 'create category' button was clicked
  $(document).on('click', '#create-category-button', function(){
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post(apiPath + "api/users/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
      // Build the form
      var create_category_html=`

          <!-- Go back button -->
          <button id='go-home-button' class='styledButton greyBtn'>
            &lt; Go Back
          </button>
          <!-- 'create category' html form -->
          <form id='create-category-form' action='#' method='post' border='0'>

            <label for="create-form-name">Name</label>
            <input type='text' name='name' id="create-form-name" required />

            <label for="create-form-description">Description</label>
            <input type='text' name='description' id="create-form-description" required />

            <button type='submit'>Save Changes</button>

          </form>`;

      // inject html to 'page-content' of our app
      $("#content").html(create_category_html);

      // chage page title
      changePageTitle("Create Category");

    })
    // show login page on error
    .fail(function(result){
      responseAlert('warning', 'Please login to access this page.');
      $("#login").click();
    });
  });

  // will run if create category form was submitted
  $(document).on('submit', '#create-category-form', function(){
    // get form data
    var form_data=JSON.stringify($(this).serializeObject());

    // submit form data to api
    $.ajax({
      url: apiPath + "api/category/create.php",
      type : "POST",
      contentType : 'application/json',
      //send jwt header
      headers: {
        Authorization: 'Bearer ' + getCookie('jwt')
      },
      data : form_data,
      success : function(result) {
        // category was created, go back to categories list
        responseAlert('success', 'Category successfully added');
        showCategoryPage();
      },
      error: function(xhr, resp, text) {
        // show error to console
        responseAlert('error', 'Create category failed with the folllowing error: ' + xhr.responseJSON.message);
        console.log(xhr, resp, text);
      }
    });
    return false;
  });

  /*
  Category detail/read one
  */
  // handle 'read one' button click
  $(document).on('click', '#list-item', function(){
    var id = $(this).attr('data-id');
    // read category record based on given ID
    $.getJSON(apiPath + "api/category/read_one.php?id=" + id, function(data){
      var read_one_category_html=`
          <!-- Go back button -->
          <button id='go-home-button' class='styledButton greyBtn'>
            &lt; Go Back
          </button>
          <!-- Update button -->
          <button id='update-category-button' class='styledButton greyBtn'  data-id='` + data.id + `'>
            Update Category
          </button>
          <!-- Delete button -->
          <button id='delete-category-button' class='styledButton greyBtn'  data-id='` + data.id + `'>
            Delete Category
          </button>
          <p>Name: ` + data.name + `</p>
          <p>Description: ` + data.description + `</p>
          `
      // inject html to 'page-content' of our app
      $("#content").html(read_one_category_html);

      // chage page title
      changePageTitle("Category Detail");

    });
  });


  /*
  Update category
  */
  // show html form when 'update category' button was clicked
  $(document).on('click', '#update-category-button', function(){

    // get category id
    var id = $(this).attr('data-id');
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post(apiPath + "api/users/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {


      // read one record based on given category id
      $.getJSON(apiPath + "api/category/read_one.php?id=" + id, function(data){

        // values will be used to fill out our form
        var name = data.name;
        var price = data.price;
        var description = data.description;
        var category_id = data.category_id;
        var category_name = data.category_name;

        //$.getJSON(apiPath + "api/category/read.php", function(data){

          // build categories list
          var categories_options_html=`<select name='category_id'>`;
          //$.each(data.records, function(key, val){
            // pre-select option is category id is the same
            //if(val.id==category_id){ categories_options_html+=`<option value='` + val.id + `' selected>` + val.name + `</option>`; }

            //else{ categories_options_html+=`<option value='` + val.id + `'>` + val.name + `</option>`; }
          //});

          categories_options_html+=`<option value='1'>Test Category</option>`; //TODO This is for testing, to be removed!!!!!

          categories_options_html+=`</select>`;

          // Build the form
          var update_category_html=`

              <!-- Go back button -->
              <button id='go-home-button' class='styledButton greyBtn'>
                &lt; Go Back
              </button>
              <!-- 'update category' html form -->
              <form id='update-category-form' action='#' method='post' border='0'>

                <label for="update-form-name">Name</label>
                <input type='text' name='name' id="update-form-name" value="` + name + `" required />

                <label for="update-form-price">Price</label>
                <input type='number' min='1' name='price' id="update-form-price" value="` + price + `" required />

                <label for="update-form-description">Description</label>
                <input type='text' name='description' id="update-form-description" value="` + description + `"required />

                <label for="update-form-category">Category</label>
                ` + categories_options_html + `

                <!-- hidden 'category id' to identify which record to delete -->
                <input value=\"` + id + `\" name='id' type='hidden' />

                <button type='submit'>Save Changes</button>

              </form>`;

          // inject html to 'page-content' of our app
          $("#content").html(update_category_html);

          // chage page title
          changePageTitle("Update Category");

        //});
      });
    })
    // show login page on error
    .fail(function(result){
      responseAlert('warning', 'Please login to access this page.');
      $("#login").click();
    });
  });

  // will run if 'create category' form was submitted
  $(document).on('submit', '#update-category-form', function(){
    // get form data
    var form_data=JSON.stringify($(this).serializeObject());

    // submit form data to api
    $.ajax({
      url: apiPath + "api/category/update.php",
      type : "POST",
      contentType : 'application/json',
      //send jwt header
      headers: {
        Authorization: 'Bearer ' + getCookie('jwt')
      },
      data : form_data,
      success : function(result) {
        // category was created, go back to categories list
        responseAlert('success', 'Category successfully updated');
        showCategoryPage();
      },
      error: function(xhr, resp, text) {
        // show error to console
        responseAlert('error', 'Update category failed with the folllowing error: ' + xhr.responseJSON.message);
        console.log(xhr, resp, text);
      }
    });


      return false;
  });

  /*
  Delete category
  */
  // will run if the delete button was clicked
  $(document).on('click', '#delete-category-button', function(){
    // get category id
    var id = $(this).attr('data-id');
    // validate jwt to verify access
    var jwt = getCookie('jwt');

    $.post(apiPath + "api/users/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
      // Build the page
      var delete_category_html=`
          <p><b>Warning!</b> are you sure you want to delete this?</p>
          <button id='go-home-button' class='styledButton greyBtn'>
            &lt; No, Go Back
          </button>
          <button id='confirm-delete-category-button' data-id='` + id + `' class='styledButton dangerBtn'>
            Yes, Delete Forever
          </button>

          `;
      // inject html to 'page-content' of our app
      $("#content").html(delete_category_html);

      // chage page title
      changePageTitle("Delete Category");
    })
    // show login page on error
    .fail(function(result){
      responseAlert('warning', 'Please login to access this page.');
      $("#login").click();
    });
  });

  // will run if the confirm delete button was clicked
  $(document).on('click', '#confirm-delete-category-button', function(){
    // get category id
    var id = $(this).attr('data-id');
    var form_data = JSON.stringify({ id: id });

    // submit form data to api
    $.ajax({
      url: apiPath + "api/category/delete.php",
      type : "POST",
      contentType : 'application/json',
      //send jwt header
      headers: {
       Authorization: 'Bearer ' + getCookie('jwt')
      },
      data : form_data,
      success : function(result) {
       // category was created, go back to categories list
       responseAlert('success', 'Category successfully deleted');
       showCategoryPage();
      },
      error: function(xhr, resp, text) {
       // show error to console
       responseAlert('error', 'Delete category failed with the folllowing error: ' + xhr.responseJSON.message);
       console.log(xhr, resp, text);
      }
   });
     return false;
  });
});
