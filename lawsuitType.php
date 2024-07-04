<?php include_once('header.php');  ?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Customers</h1>
	  <!--
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Customers Types</li>
        </ol>
      </nav>
	  -->
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <!--<h5 class="card-title">Recent Sales <span>| Today</span></h5> -->
                  <h3 class="card-title">
					  <button type="button" class="btn btn-primary btn-sm"><i class="ri-add-fill"></i> Add New Customer</button>
					  <button type="button" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Add New Customer Type</button>
					</h3>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">Name AR	</th>
                        <th scope="col">Name EN	</th>
                        <th scope="col">Number of Lawsuits	</th>
                        <th scope="col">Action</th>
                        <th scope="col">Created At	</th>
                        <th scope="col">User</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#">فرد</a></th>
                        <td scope="row"><a href="#">individual	</a></td>
                        <td>4</td>
                        <td><span class="badge bg-success">Edit</span><span class="badge bg-danger">Delete</span></td>
                        <td>2023-05-20 09:34:44</td>
                        <td>admin</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">شركة</a></th>
                        <td scope="row"> <a href="#">company</a> </td>
                        <td>5</a></td>
                        <td><span class="badge bg-success">Edit</span><span class="badge bg-danger">Delete</span></td>
                        <td>2023-05-20 09:34:44</span></td>
                        <td>admin</td>
                      </tr>
                      <tr>
						<th scope="row"><a href="#">مؤسسة</a></th>
                        <td scope="row"><a href="#">fdfd</a></td>
                        <td>1</td>
                        <td><span class="badge bg-success">Edit</span><span class="badge bg-danger">Delete</span></td>
                        <td>2023-05-28 17:05:34	</td>
                        <td>admin</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">جمعية خيرية	</a></th>
                        <td scope="row"> <a href="#">hdrh	</a> </td>
                        <td>0</a></td>
                        <td><span class="badge bg-success">Edit</span><span class="badge bg-danger">Delete</span></td>
                        <td>2023-06-07 01:06:28	</span></td>
                        <td>admin</td>
                      </tr>
					  
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

      </div>
    </section>

  </main><!-- End #main -->

<?php include_once('footer.php'); ?>