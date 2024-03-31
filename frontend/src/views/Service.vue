<template>
	<Top/>
	<spinner/>
	<navbar />
	<div class="container-xxl bg-white p-0">
		<div class="service">
			<div class="container-fluid page-header mb-5 p-0">
				<div class="container-fluid page-header-inner py-5">
					<div class="container text-center pb-5">
						<h1 class="display-3 text-white mb-3 animated slideInDown">
							Services
						</h1>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb justify-content-center text-uppercase">
								<li class="breadcrumb-item">
									<a href="#">
										Home
									</a>
								</li>
								<li class="breadcrumb-item">
									<a href="#">
										Pages
									</a>
								</li>
								<li class="breadcrumb-item text-white active" aria-current="page">
									contact_number
								</li>
							</ol>
						</nav>
					</div>
				</div>	

	<!-- Page Header End -->
			</div>
		</div>
	</div>
	<!-- Page Header End -->
	<!-- Service Start -->
	<div class="container-xxl py-5">
		        <!-- Enrollment success/error message -->
						<!-- <div class="row justify-content-center mt-3">
            <div class="col-lg-8">
                <div v-if="enrollmentMessage" class="alert" :class="{'alert-success': isSuccessMessage, 'alert-danger': !isSuccessMessage}">
                    {{ enrollmentMessage }}
                </div>
            </div>
        </div> -->
		<div class="container">
			<div class="text-center wow fadeInUp" data-wow-delay="0.1s">
				<h6 class="section-title text-center text-primary text-uppercase">
					Our Services
				</h6>
				<h1 class="mb-5">
					Explore Our
					<span class="text-primary text-uppercase">
						Services
					</span>
				</h1>
			</div>
			<div class="row g-4">
				<div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
					<a class="service-item rounded" href="">
						<div class="service-icon bg-transparent border rounded p-1">
							<div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
								<i class="fa fa-hotel fa-2x text-primary">
								</i>
							</div>
						</div>
						<h5 class="mb-3">
							Rooms & Appartment
						</h5>
						<p class="text-body mb-0">
							"Welcome to our luxurious and comfortable Rooms & Apartments at Eduardo's
							Resort. Our carefully designed accommodations are the perfect haven for
							relaxation and rejuvenation. From spacious rooms with breathtaking views
							to well-appointed apartments that feel like a home away from home, we offer
							a range of options to suit your every need. "
						</p>
					</a>
				</div>
				<div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
					<a class="service-item rounded" href="/shop">
						<div class="service-icon bg-transparent border rounded p-1">
							<div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
								<i class="fa fa-utensils fa-2x text-primary">
								</i>
							</div>
						</div>
						<h5 class="mb-3">
							Food & Restaurant
						</h5>
						<p class="text-body mb-0">
							"Indulge in a culinary journey at our resort's Food & Restaurant, where
							exceptional flavors and exquisite dining experiences await you. Our expert
							chefs artfully prepare a diverse range of dishes that tantalize your taste
							buds, from delectable international cuisine to locally inspired delicacies.
							"
						</p>
					</a>
				</div>
			</div>
			<br>
			<div class="row g-4">
				<div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
					<a class="service-item rounded" href="/swimminglesson">
    <div class="service-icon bg-transparent border rounded p-1">
        <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
            <i class="fa fa-swimmer fa-2x text-primary"></i>
        </div>
    </div>
    <h5 class="mb-3">Swimming Lesson</h5>
    <p class="text-body mb-0">
        "Dive into a world of aqua adventures at our resort's Swimming Lessons!
        Whether you're a beginner looking to conquer the waters or an experienced
        swimmer seeking to refine your skills, our certified instructors are here
        to guide you through an exciting and educational journey."
    </p>
</a>

</div>

				<div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
					<a class="service-item rounded" href="">
						<div class="service-icon bg-transparent border rounded p-1">
							<div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
								<i class="fa fa-glass-cheers fa-2x text-primary">
								</i>
							</div>
						</div>
						<h5 class="mb-3">
							Event & Party
						</h5>
						<p class="text-body mb-0">
							"Experience unforgettable moments at our resort's Events and Party offerings.
							Whether you're celebrating a special occasion, hosting a corporate event,
							or simply looking for a place to unwind with friends and family, our resort
							provides the perfect backdrop for your gatherings. "
						</p>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Service End -->
	<feedbacks/>
	<!-- Newsletter Start -->
	<div class="container newsletter mt-5 wow fadeIn" data-wow-delay="0.1s">
		<div class="row justify-content-center">
			<div class="col-lg-10 border rounded p-1">
				<div class="border rounded text-center p-1">
					<div class="bg-white rounded text-center p-5">
						<h4 class="mb-4">
							Subscribe Our
							<span class="text-primary text-uppercase">
								Newsletter
							</span>
						</h4>
						<div class="position-relative mx-auto" style="max-width: 400px;">
							<input class="form-control w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
							<button type="button" class="btn btn-primary py-2 px-3 position-absolute top-0 end-0 mt-2 me-2">
								Submit
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Newsletter Start -->
	<End/>
	<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
		<i class="bi bi-arrow-up">
		</i>
	</a>
	<div class="col-12">
		<div class="modal" v-if="isAddRoomModalOpen" tabindex="-1" role="dialog" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enrollment Form</h5>
                <button type="button" class="close btn-danger" @click="closeAddRoomModal">&times;</button>
            </div>
            <div class="modal-body">
							<form class="enrollment-form" @submit.prevent="save">
                    <div class="form-group">
                        <label for="fullname">Full Name:</label>
                        <input type="text" class="form-control" id="fullname" v-model="fullName" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number:</label>
                        <input type="tel" class="form-control" id="contact_number" v-model="contact_number" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" class="form-control" id="age" v-model="age" required>
                    </div>
                    <div class="form-group">
                        <label for="experience">Swimming Experience:</label>
                        <textarea class="form-control" id="experience" v-model="experience" rows="3" required></textarea>
                    </div>
                    <!-- <div class="form-group">
    <label for="lesson_date">Select Lesson Date:</label>
    <select class="form-control" v-model="lesson_date" required>
        <option value="" disabled>Select a date</option>
        <option v-for="date in date" :key="date.value" :value="date.value">{{ date.availableDates }}</option>
    </select>
</div> -->
<div class="form-group">
    <label for="lesson_date">Select Lesson Date:</label>
    <select class="form-control" id="lesson_date" v-model="lessonDate" required>
        <option value="" disabled>Select a date</option>
        <option value="2024-03-17">March 17, 2024</option>
        <option value="2024-03-18">March 18, 2024</option>
        <!-- Add more options as needed -->
    </select>
</div>
<br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>

    </div>
    <Notification
  :show="notification.show"
  :type="notification.type"
  :message="notification.message"
/>
</template>


<script>
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'bootstrap';
    import Top from '@/components/Top.vue';
    import navbar from '@/components/navbar.vue';
    import End from '@/components/End.vue';
    import feedbacks from '@/components/feedbacks.vue';
    import spinner from '@/components/spinner.vue';

    import axios from 'axios';

    export default {
        name: 'service',
        components: {
            spinner,
            Top,
            navbar,
            End,
            feedbacks
        },
				data() {
    return {
        enrollmentMessage: "",
        successMessage: null,
            flatpickrInstance: null,
            selectedDate: null,
            dates: [], // date of swimming 
        feed: [],
      
        fullName: '',
        contact_number: '',
        age: '',
        experience: '',
        lessonDate: '', // Add lessonDate property
        swimming_date: "", // Add swimming_date to data
        enrollmentMessage: '',
        errorMessage: '', // Define errorMessage variable
        isSuccessMessage: false // Initialize isSuccessMessage
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
    },




            async getFeed() {
                const [g, n] = await Promise.all([axios.get("/getFeedback"), axios.get("/getData")]);
                this.feed = g.data;
                this.name = n.data;
            },
            getName(g) {
                return this.name.find(n => n.id === g.id) || {};
            },
            openAddRoomModal() {
                this.isAddRoomModalOpen = true; // Set to true to open the modal
            },
            closeAddRoomModal() {
                this.isAddRoomModalOpen = false; // Set to false to close the modal
            },
        }
    }
</script>
<style>
	@import '@/assets/css/bootstrap.min.css'; @import '@/assets/css/style.css';
	.service { background-image: url('~@/assets/img/pool4.jpg'); background-size:
	cover; background-repeat: no-repeat; background-position: center center;
	width: 100%; height: 338px; }


	    /* Form styling */
			.enrollment-form {
        max-width: 400px;
        margin: 0 auto;
    }
    .enrollment-form label {
        margin-bottom: 0.5rem;
        font-weight: bold;
    }
    .enrollment-form input[type="text"],
    .enrollment-form input[type="email"],
    .enrollment-form input[type="tel"],
    .enrollment-form input[type="number"],
    .enrollment-form textarea {
        width: 100%;
        padding: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .enrollment-form textarea {
        resize: vertical; /* Allow vertical resizing */
    }
    .enrollment-form button[type="submit"] {
        display: block;
        width: 100%;
        padding: 0.75rem;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
    }
</style>
<script>
    import Top from '@/components/Top.vue';
    import navbar from '@/components/navbar.vue';
    import End from '@/components/End.vue';
    import feedbacks from '@/components/feedbacks.vue';
    import spinner from '@/components/spinner.vue';
	import Notification from '@/components/Notification.vue';

    import axios from 'axios';

    export default {
        name: 'service',
        components: {
            spinner,
            Top,
            navbar,
            End,
            feedbacks,
            Notification
        },
				data() {
    return {
        availableDates: [], // add availableDates array

        notification: {
      show: false,
      type: "", // "success" or "error"
      message: "",
    },
        feed: [],
        isAddRoomModalOpen: false, // Initialize as false
        fullName: '',
        contact_number: '',
        age: '',
        experience: '',
        lesson_date: '', // Add lessonDate property
        enrollmentMessage: '',
        errorMessage: '', // Define errorMessage variable
        isSuccessMessage: false // Initialize isSuccessMessage
    };
},
created() {
        this.fetchAvailableDates(); // Fetch available dates when the component is created
    },
methods: {
    async fetchAvailableDates() {
        const r = await axios.get("/getAvailableDates");
				this.date = r.data;
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
                lesson_date: this.lesson_date,
            });

            if (response.status === 200) {
                this.enrollmentMessage = response.data.message;
                this.isSuccessMessage = true; // Set to true for success message
                // Clear form fields
                this.fullName = "";
                this.contact_number = "";
                this.age = "";
                this.experience = "";
                this.lesson_date = "";
                this.isAddRoomModalOpen = false;
                this.showSuccessNotification("Enrolled Successfull, Wait for the Admins confirmation");

                setTimeout(() => {
        this.successMessage = "";
      }, 2000);
    } else {
        this.showErrorNotification("Enrollment full");
    }
  } catch (error) {
    console.error("Error adding to cart", error);
  }
},  showSuccessNotification(message) {
    this.notification = {
      show: true,
      type: "success",
      message: message,
    };

    setTimeout(() => {
      this.notification.show = false;
    }, 2000);
  },
  showErrorNotification(message) {
    this.notification = {
      show: true,
      type: "error",
      message: message,
    };

    setTimeout(() => {
      this.notification.show = false;
    }, 2000);
  },


            async getFeed() {
                const [g, n] = await Promise.all([axios.get("/getFeedback"), axios.get("/getData")]);
                this.feed = g.data;
                this.name = n.data;
            },
            getName(g) {
                return this.name.find(n => n.id === g.id) || {};
            },
            openAddRoomModal() {
                this.isAddRoomModalOpen = true; // Set to true to open the modal
            },
            closeAddRoomModal() {
                this.isAddRoomModalOpen = false; // Set to false to close the modal
            },
        }
    }
</script>
