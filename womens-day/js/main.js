$(window).load(function () {
    var offSet = $('.right-menu').offset().top;
    $(window).scroll(function () {
        if ($(this).scrollTop() > offSet) {
            $('.right-menu').addClass('sticky');
        }
        else {
            $('.right-menu').removeClass('sticky');
        }
    });
});
// Function to reveal lightbox and adding YouTube autoplay
function revealVideo(div, video_id) {
    var video = document.getElementById(video_id).src;
    document.getElementById(video_id).src = video + '&autoplay=1'; // adding autoplay to the URL
    document.getElementById(div).style.display = 'block';
}
// Hiding the lightbox and removing YouTube autoplay
function hideVideo(div, video_id) {
    var video = document.getElementById(video_id).src;
    var cleaned = video.replace('&autoplay=1', ''); // removing autoplay form url
    document.getElementById(video_id).src = cleaned;
    document.getElementById(div).style.display = 'none';
}