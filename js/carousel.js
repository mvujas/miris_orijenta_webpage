var Carousel = function(selector, intervalUpdate=false, updateTime=1000) {
  this.carousel = $(selector);
  this.slides = this.carousel.find(".carousel-item");
  this.currentSlide = 1;
  this.interval;
  this.updateTime = updateTime;
  if(intervalUpdate)
    this.interval = setInterval(this.nextSlide.bind(this), this.updateTime);
};
Carousel.prototype = {
  showSlide: function (n) {
    if(n > this.slides.length)
      n = 1;
    if(n < 1)
      n = this.slides.length;
    if(n === this.currentSlide)
      return;
    $(this.slides.get(this.currentSlide - 1)).fadeOut("slow");
    $(this.slides.get(n - 1)).fadeIn("slow");
    this.currentSlide = n;
  },
  nextSlide: function() {
    this.showSlide(this.currentSlide + 1);
  },
  prevSlide: function() {
    this.showSlide(this.currentSlide - 1);
  }
};
