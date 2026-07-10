// Function to handle the rating submission
function submitRating(instructorId, rating) {
    // Sending the rating value to the server via AJAX
    let formData = new FormData();
    formData.append('instructor_id', instructorId);
    formData.append('rating', rating);

    fetch('submit_rating.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('امتیاز شما ثبت شد!');
            location.reload();  // Reload the page to update ratings
        } else {
            alert('مشکلی پیش آمده است. لطفاً دوباره تلاش کنید.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطا در ارسال داده.');
    });
}

// Function to handle the rating click event
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        let rating = this.getAttribute('data-rating');
        let instructorId = this.getAttribute('data-instructor-id');

        // Call the submitRating function when a star is clicked
        submitRating(instructorId, rating);
    });
});
