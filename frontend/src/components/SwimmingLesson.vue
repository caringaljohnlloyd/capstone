<template>
 <Top/>
    <spinner/>
      <navbar />
        <!-- Page Header Start -->
        <div class="container-xxl bg-white p-0">
        <div class="contact">
        <div class="container-fluid page-header mb-5 p-0" >
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Swimming Lesson</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
            </div>
        <!-- Page Header End -->


        <!-- Swimming Lesson Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Enroll Now!</h6>
                    <h1 class="mb-5"><span class="text-primary text-uppercase">Enrollment</span> FORM</h1>
                </div>
                <div class="row g-4">
  
                  <div class="col-lg-6">
          <div class="row g-3">
            <div class="col-6 text-end">
              <img :src="require('../assets/img/pool3.jpg')" alt="" class="img-fluid rounded w-100 wow zoomIn"
                data-wow-delay="0.1s" />
            </div>
            <div class="col-6 text-start">
              <img :src="require('../assets/img/pool5.jpg')" alt="" class="img-fluid w-100 wow zoomIn"
                data-wow-delay="0.3s" />
            </div>
            <div class="col-6 text-end">
              <img :src="require('../assets/img/band.jpg')" alt="" class="img-fluid rounded w-75 wow zoomIn"
                data-wow-delay="0.5s" />
            </div>
            <div class="col-6 text-start">
              <img :src="require('../assets/img/pool2.jpg')" alt="" class="img-fluid rounded w-100 wow zoomIn"
                data-wow-delay="0.7s" />
            </div>
          </div>
        </div>
                    
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.2s">
 
<form class="enrollment-form" @submit.prevent="save">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            
                                            <input type="text" class="form-control" id="fullname" v-model="fullName" required>
                                            <label for="fullname">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                          <input type="number" class="form-control" id="age" v-model="age" required>
                                            <label for="age">Age</label>
                                          
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                       <input type="tel" class="form-control" id="contact_number" v-model="contact_number" required>
                                            <label for="contact_number">Contact Number</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                          <textarea class="form-control" id="experience" v-model="experience" rows="3" required></textarea>
                                            <label for="message">Swimming Experience</label>
                                        </div>
                                    </div>
                                    <div class="form-floating">
                                  <br>  
            <label for="swimming_date">Swimming Date: </label>
            <br>
            <input type="date" class="form-control" placeholder="Select Date" ref="datepicker" v-model="swimming_date" required>
        </div>

                                    <div class="col-12">
                                      <button type="submit" class="btn btn-primary w-100 py-3" style="    color: white;
    background-color: #FEA116;
    border-color: #FEA116;" >Submit</button>

        <div v-if="enrollmentMessage" class="message" style="
color: white; background-color:green;

">{{ enrollmentMessage }}</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Swimming Lesson End -->


        <!-- Newsletter Start -->
        <div class="container newsletter mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="row justify-content-center">
                <div class="col-lg-10 border rounded p-1">
                    <div class="border rounded text-center p-1">
                        <div class="bg-white rounded text-center p-5">
                            <h4 class="mb-4">Subscribe Our <span class="text-primary text-uppercase">Newsletter</span></h4>
                            <div class="position-relative mx-auto" style="max-width: 400px;">
                                <input class="form-control w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
                                <button type="button" class="btn btn-primary py-2 px-3 position-absolute top-0 end-0 mt-2 me-2">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Newsletter Start -->
      <End/>
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
</template>

<script>
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'bootstrap';
    import Top from '@/components/Top.vue';
    import navbar from '@/components/navbar.vue';
    import End from '@/components/End.vue';
    import spinner from '@/components/spinner.vue';

    import axios from 'axios';

    export default {
        name: 'swimminglesson',
        components: {
            spinner,
            Top,
            navbar,
            End,
      
        },
				data() {
    return {
        enrollmentMessage: "",
        successMessage: null,
            flatpickrInstance: null,
            selectedDate: null,
            dates: [], // date of swimming 
          };
},
mounted() {
    // Fetch dates from the server
    this.getDate();
},
created() {
    this.getDate();
},
methods: {
    openDatePicker() {
        // Open the flatpickr calendar when the input field is clicked
        this.flatpickrInstance.open();
    },
    async getDate() {
        try {
            const response = await axios.get("/getDate");
            if (response && response.status === 200 && Array.isArray(response.data)) {
                this.dates = response.data; // Assuming the dates are in the correct format
                this.initFlatpickr();
            } else {
                console.error('Error fetching dates: Response data is empty or not an array');
            }
        } catch (error) {
            console.error('Error fetching dates:', error);
            // Handle error if needed
        }
    },
    initFlatpickr() {
        // Check if this.dates is not null or undefined
        if (this.dates) {
            // Initialize flatpickr instance
            this.flatpickrInstance = flatpickr(this.$refs.datepicker, {
                dateFormat: 'Y-m-d',
                enable: this.dates, // Enable dates from the 'dates' array
                onClose: selectedDates => {
                    this.selectedDate = selectedDates[0];
                },
                onReady: (selectedDates, dateStr, instance) => {
                    // Add custom class to selected dates for styling
                    selectedDates.forEach(date => {
                        const dateElem = instance.days.querySelector(`[data-date="${date}"]`);
                        if (dateElem) {
                            dateElem.classList.add('custom-selected-date');
                        }
                    });
                }
            });
        } else {
            console.error('Dates array is null or undefined');
        }
    },
    closeAddRoomModal() {
        // Close modal
        this.isAddRoomModalOpen = false;
    },
    async save() {
        try {
            const id = sessionStorage.getItem("id");
            const response = await axios.post("enroll", {
                id: id,
                fullName: this.fullName,
                contact_number: this.contact_number,
                age: this.age,
                experience: this.experience,
                lesson_date: this.formatDate(this.selectedDate), // Use the modified formatDate function
            });
            if (response.status === 200) {
    // Set success message and clear form fields
    this.enrollmentMessage = "Successfully enrolled";
    this.isSuccessMessage = true;
    this.clearFormFields();

    // Clear the success message after 2 seconds
    setTimeout(() => {
        this.enrollmentMessage = "";
        this.isSuccessMessage = false;
    }, 2000);
}

        } catch (error) {
            console.error("Error submit", error);
            if (error.response && error.response.status === 400) {
                this.errorMessage = error.response.data.message || "Submit failed";
            } else {
                this.errorMessage = "Error submit";
            }
            this.enrollmentMessage = ""; // Clear enrollmentMessage on error
        }
    },
    clearFormFields() {
        // Clear form fields
        this.fullName = "";
        this.contact_number = "";
        this.age = "";
        this.experience = "";
        this.selectedDate = ""; // Clear the selected date
    },
    formatDate(date) {
        // Format the date to YYYY-MM-DD
        return date.toISOString().split('T')[0];
    }
  }
}
</script> 
<style scoped>
 @import '@/assets/css/bootstrap.min.css';
 @import '@/assets/css/style.css';
.contact {
  background-image: url('~@/assets/img/pool4.jpg'); 
  background-size: cover; 
  background-repeat: no-repeat; 
  background-position: center center; 
  width: 100%; 
  height: 338px; 
}
.btn-primary {
    color: #000;
    background-color: #FEA116;
    border-color: #FEA116;
}
</style>
