import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
gsap.registerPlugin(ScrollTrigger);

(function ($) {
  let $mainMenu = $(".main-menu");
  let $mainMenuToggle = $(".menu-toggle");
  let menuOpen = false;

  $mainMenuToggle.on("click", function () {
    if (!menuOpen) {
      let menuTl = gsap.timeline();
      menuTl.to(".magic-menu", { height: "100%", display: "block", duration: 0 });
      menuTl.fromTo(
        [".magic-menu__underlay", ".magic-menu__background"],
        {
          height: 0,
          transformOrigin: "right top",
          skewY: 4,
          ease: "power3.inOut",
        },
        {
          duration: 0.8,
          height: "100%",
          transformOrigin: "right top",
          skewY: 0,
          ease: "power3.inOut",
          stagger: {
            amount: 0.1,
          },
        }
      );
      menuTl.fromTo(
        ".magic-menu .main-menu li",
        {
          opacity: 0,
          x: -100,
        },
        {
          opacity: 1,
          x: 0,
          duration: 0.3,
          stagger: {
            amount: 0.4,
          },
        },
        "-=.3"
      );

      menuTl.fromTo(
        ".magic-menu .text",
        {
          opacity: 0,
          y: 50,
        },
        {
          opacity: 1,
          y: 0,
          duration: 0.5,
        },
        "-=.3"
      );

      menuTl.fromTo(
        ".magic-menu .portrait",
        {
          opacity: 0,
          x: 50,
        },
        {
          opacity: 1,
          x: 0,
          duration: 0.5,
        },
        "-=.3"
      );

      menuOpen = true;
    } else {
      let menuTl = gsap.timeline();

      menuTl.fromTo(
        ".magic-menu .portrait",
        {
          opacity: 1,
          x: 0,
        },
        {
          opacity: 0,
          x: 50,
          duration: 0.5,
        }
      );

      menuTl.to(
        ".magic-menu .text",
        {
          opacity: 0,
          y: 50,
          duration: 0.3,
        },
        "-=.3"
      );
      menuTl.to(
        ".magic-menu .main-menu li",
        {
          opacity: 0,
          x: -100,
          duration: 0.2,
          stagger: {
            amount: 0.4,
          },
        },
        "-=.3"
      );
      menuTl.to([".magic-menu__background", ".magic-menu__underlay"], {
        duration: 0.8,
        height: 0,
        transformOrigin: "right top",
        skewY: 4,
        ease: "power3.inOut",
        stagger: {
          amount: 0.1,
        },
      });
      menuTl.to(".magic-menu", { height: 0, display: "none", duration: 0 });
      menuOpen = false;
    }
  });

  // About animation

  $(".project-slider .slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    arrows: false,
    autoplay: true,
    autoplaySpeed: 3000,
    fade: true,
    customPaging: function (slider, i) {
      return $('<div class="dot"  />');
    },
  });

  var $modal = $("<div>", { class: "content-modal" });
  var $modalframe = $("<div>", { class: "content-modal__frame" });
  var $modalcontent = $("<div>", { class: "content-modal__content" });
  var $modalclose = $("<div>", { class: "content-modal__close" });
  $modal.append($modalframe);
  $modalframe.append($modalclose);
  $modalframe.append($modalcontent);
  $modal.hide();

  $modal.on("click", function (e) {
    if (e.target === this) {
      $modal.hide();
    }
  });

  $modalclose.on("click", function (e) {
    if (e.target === this) {
      $modal.hide();
    }
  });

  $("body").append($modal);

  function openModal(posttype, postid) {
    $.ajax({
      url: "/wp-json/wp/v2/" + posttype + "s/" + postid + "?_embed&acf_format=standard",
      beforeSend: function () {
        $modalcontent.html('<div class="content-modal__spinner"></div>');
        $modal.fadeIn();
      },
      success: function (data) {
        console.log(data);
        var html = "";
        if (data.acf.bild_1 !== false) {
          html += '<div class="slider">';
          Object.entries(data.acf).forEach((el) => {
            console.log(el);
            if (el[0].startsWith("bild_") && el[1]) {
              html += '<img src="' + el[1].sizes.large + '">';
            }
          });
          html += "</div>";
        }
        html += '<h3 class="content-modal__title">' + data.title.rendered + "</h3>";
        html += '<div class="content-modal__text">' + data.content.rendered + "</div>";
        $modalcontent.html(html);
        $modalcontent.find(".slider").slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: true,
          arrows: true,
          nextArrow: '<div class="arrow next"></div>',
          prevArrow: '<div class="arrow prev"></div>',
          autoplay: true,
          autoplaySpeed: 3000,
          fade: true,
          customPaging: function (slider, i) {
            return $('<div class="dot"  />');
          },
        });
      },
    });
  }

  // $(".ecs-posts article").on("click", function (e) {
  //   e.preventDefault();
  //   var postid = $(this).attr("id").split("-")[1];
  //   var posttype = $(this)
  //     .attr("class")
  //     .match(/type[\w-]*\b/)[0]
  //     .split("-")[1];
  //   openModal(posttype, postid);
  // });

  // $(".project-slider.multiple .project-slider__project").on("click", function (e) {
  //   e.preventDefault();
  //   var postid = $(this).attr("data-post").split("-")[1];
  //   var posttype = "projekt";
  //   openModal(posttype, postid);
  // });

  // look for later ajax loaded elements
  // $(".modal-popup .ecs-posts").on("DOMSubtreeModified", function (e) {
  //   if (e.target.innerHTML.length > 0) {
  //     $(".modal-popup article").on("click", function (e) {
  //       e.preventDefault();
  //       var postid = $(this).attr("id").split("-")[1];
  //       openModal("projekt", postid);
  //     });
  //   }
  // });

  if (document.querySelector(".experience-animation") !== null) {
    let img = document.querySelector(".experience-animation .video");

    let targets = gsap.utils.toArray(".ani");

    targets.forEach((ani, i) => {
      // not the last slide
      if (i + 1 < targets.length) {
        gsap.to(ani, {
          autoAlpha: 0,
          scrollTrigger: {
            trigger: ".slide" + (i + 1),
            start: "center center",
            stop: "center bottom",
            scrub: true,
          },
        });
      }
    });

    // https://codepen.io/GreenSock/pen/oNGQMEm
    if (document.querySelector(".experience-animation")) {
      let frameCount = 79;
      const currentFrame = (index) => `/wp-content/themes/usomo/assets/img/animation/frame_${(index + 1).toString().padStart(5, "0")}.png`;

      let images = [];
      let animation = {
        frame: 0,
      };

      for (let i = 0; i < frameCount; i++) {
        let img = new Image();
        img.src = currentFrame(i);
        images.push(img);
      }

      gsap
        .timeline({
          onUpdate: render,
          scrollTrigger: {
            trigger: ".experience-animation",
            start: "top top",
            end: "bottom bottom",
            scrub: 0.5,
          },
        })
        .to(animation, {
          frame: frameCount - 1,
          snap: "frame",
          ease: "none",
          duration: 1,
        });

      function render() {
        console.log(animation.frame);
        img.src = currentFrame(animation.frame);
      }
    }

    let video = document.querySelector(".headphones .video");

    let tl = gsap.timeline({
      defaults: { duration: 1 },
      scrollTrigger: {
        trigger: ".experience-animation",
        start: "top top",
        end: "bottom bottom",
        scrub: true,
      },
    });

    // //https://greensock.com/forums/topic/25730-scrub-through-video-smoothly-scrolltrigger/
    // tl.fromTo(
    //   video,
    //   {
    //     currentTime: 0,
    //   },
    //   {
    //     currentTime: video.duration || 1,
    //   }
    // );

    let tl2 = gsap.timeline({
      scrollTrigger: {
        trigger: ".experience-animation",
        start: "top top",
        end: "bottom bottom",
        scrub: true,
      },
    });

    tl2.to(video, {
      x: 0,
      duration: 1,
    });

    tl2.to(video, {
      x: "-50%",
      duration: 2,
    });
    tl2.to(video, {
      x: "-50%",
      duration: 8,
    });

    tl2.to(video, {
      x: "50%",
      duration: 2,
    });

    tl2.to(video, {
      x: "50%",
      duration: 2,
    });
  }
})(jQuery);
