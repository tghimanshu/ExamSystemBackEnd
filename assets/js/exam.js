function getFiftyId(qlen) {
  const attemptableq = [];
  while (attemptableq.length < 50) {
    let a = Math.floor(Math.random() * qlen);
    if (!attemptableq.includes(a)) {
      attemptableq.push(a);
    }
  }
  return attemptableq;
}

$(document).ready(function () {
  // CHECKING FOR KAANDS
  let superErrorContainer = $("#superErrorContainer");
  function resizeevent(e) {
    if (
      window.outerHeight === window.screen.availHeight &&
      window.outerWidth === window.screen.availWidth
    ) {
      // superErrorContainer.html("");
    } else {
      // superErrorContainer.html("<div id='superError'></div>");
      $.post(
        "answer_update.php",
        {
          type: "get_attempt",
          studentId: student_id,
          paperId: paper_id,
        },
        function (data, status, xhr) {
          console.log("data; " + data);
          Swal.fire({
            icon: "",
            title: `Attempts Used ${parseInt(data) + 1}/5`,
            text: "Your tried resizing the tab",
            allowEscapeKey: false,
            allowOutsideClick: false,
            preConfirm: (login) => {
              $.post(
                "answer_update.php",
                {
                  type: "deduct_attempt",
                  studentId: student_id,
                  paperId: paper_id,
                },
                function (data, status, xhr) {
                  if (parseInt(data) > 4) {
                    $.post(
                      "answer_update.php",
                      {
                        type: "submit_exam",
                        studentId: student_id,
                        paperId: paper_id,
                      },
                      function (data, status, xhr) {
                        localStorage.removeItem("timeElapsed");
                        // $(window).off("unload");
                        $(window).off("beforeunload");
                        window.location = "index.php";
                      }
                    );
                    localStorage.removeItem("timeElapsed");
                  }
                }
              );
            },
          });
        }
      );
    }
  }

  if (!navigator.userAgent.toLowerCase().includes("android")) {
    window.addEventListener("load", resizeevent);
    window.addEventListener("resize", resizeevent);
  }
  $(window).blur(function () {
    // superErrorContainer.html("<div id='superError'></div>");
    $.post(
      "answer_update.php",
      {
        type: "get_attempt",
        studentId: student_id,
        paperId: paper_id,
      },
      function (data, status, xhr) {
        console.log("data; " + data);
        Swal.fire({
          icon: "",
          title: `Attempts Used ${parseInt(data) + 1}/5`,
          text: "Your tried leaving the tab",
          allowEscapeKey: false,
          allowOutsideClick: false,
          preConfirm: (login) => {
            $.post(
              "answer_update.php",
              {
                type: "deduct_attempt",
                studentId: student_id,
                paperId: paper_id,
              },
              function (data, status, xhr) {
                if (parseInt(data) > 4) {
                  $.post(
                    "answer_update.php",
                    {
                      type: "submit_exam",
                      studentId: student_id,
                      paperId: paper_id,
                    },
                    function (data, status, xhr) {
                      localStorage.removeItem("timeElapsed");
                      $(window).off("unload");
                      $(window).off("beforeunload");
                      window.location = "index.php";
                    }
                  );
                  localStorage.removeItem("timeElapsed");
                }
              }
            );
          },
        });
      }
    );
  });

  $("body").on("contextmenu", function (e) {
    return false;
  });

  document.addEventListener("keydown", function (event) {
    if (event.which == "123") {
      event.preventDefault();
    }
    if (event.ctrlKey || event.shiftKey || event.altKey) {
      // event.preventDefault();
      // superErrorContainer.html("<div id='superError'></div>");
      // alert("you cant click me")
    } else {
      superErrorContainer.html("");
    }
  });

  // END OF CHECKING FOR KAANDS
  let student_id = $("#student_id").text();
  let paper_id = $("#paper_id").text();
  $(window).on("beforeunload", function () {
    return "Are you sure want to Exit the exam ?";
  });

  $(window).on("unload", function () {
    $.post(
      "answer_update.php",
      {
        type: "submit_exam",
        studentId: student_id,
        paperId: paper_id,
      },
      function (data, status, xhr) {
        localStorage.removeItem("timeElapsed");
        window.location = "index.php";
      }
    );
    localStorage.removeItem("timeElapsed");
  });
  let dbAnswers =
    $("#answers").text() === ""
      ? Array()
      : JSON.parse($("#answers").text()).map((ans) => {
          return {
            qId: parseInt(ans.qId),
            answer: parseInt(ans.answer),
            review: ans.review === "true" ? true : false,
          };
        });

  let questionsDiv = $("#questions");
  let questionsFetched = $("#questions_fetched").text();
  let imagesDir = $("#imagesDir").text();
  let questionsData = JSON.parse(JSON.parse(questionsFetched));
  let qids =
    dbAnswers.length === 0
      ? getFiftyId(questionsData.length)
      : dbAnswers.map((q) => q.qId);
  let answerSheet =
    dbAnswers.length === 0
      ? qids.map((id) => ({
          qId: id,
          answer: 0,
          review: false,
        }))
      : dbAnswers;

  const generateAnswersTracker = () => {
    document.getElementById("tracker").innerHTML = "";
    answerSheet.map((val, i) => {
      document.getElementById("tracker").innerHTML += `
    <div class="col-2"><div id="at-${i}" style="cursor:pointer;" class="anstab mb-1 badge bg-${
        val.answer === 0 && val.review === true
          ? "primary"
          : val.answer === 0 && val.review === false
          ? "light"
          : val.answer !== 0 && val.review === true
          ? "info"
          : "success"
      } text-${
        val.answer === 0 && val.review === true
          ? "light"
          : val.answer === 0
          ? "dark"
          : val.answer !== 0 && val.review === true
          ? "dark"
          : "light"
      }">${i + 1}</div></div>`;
    });
    $(".anstab").on("click", function (e) {
      let id = e.target.getAttribute("id").split("-")[1];
      $(`.question`).each(function (i) {
        if ($(this).css("display") === "block") {
          updateAnswerSheet(i);
        }
        $(this).css("display", "none");
      });
      $(`#q${parseInt(id)}`).css("display", "block");
      $(`#at-${id}`).addClass("btn-dark");

      // updating id
    });
  };
  generateAnswersTracker();
  qids.map((id, i) => {
    const ans = answerSheet[i];
    questionsDiv[0].innerHTML += `
                        <div id="q${i}" class="question">
                            <h5>${i + 1}. ${
      questionsData[id].question.trim() === ""
        ? "<img src='" +
          imagesDir +
          "/" +
          (parseInt(questionsData[id].qId) + 1) +
          ".jpg' width='75%' height='auto' />"
        : questionsData[id].question
    }</h5>
                            <div class="input-group">
                                <input name="o-${i}" type="radio" ${
      ans.answer === 1 ? "checked" : ""
    } id="o-${i}-1" value="1" class="form-check mr-2" />
                                <label for="o-${i}-1" class="form-check-label">${
      questionsData[id].answer1
    }</label>
                            </div>
                            <div class="input-group">
                                <input name="o-${i}" type="radio" ${
      ans.answer === 2 ? "checked" : ""
    } id="o-${i}-2" value="2" class="form-check mr-2" />
                                <label for="o-${i}-2" class="form-check-label">${
      questionsData[id].answer2
    }</label>
                            </div>
                            <div class="input-group">
                                <input name="o-${i}" type="radio" ${
      ans.answer === 3 ? "checked" : ""
    } id="o-${i}-3" value="3" class="form-check mr-2" />
                                <label for="o-${i}-3" class="form-check-label">${
      questionsData[id].answer3
    }</label>
                            </div>
                            <div class="input-group">
                                <input name="o-${i}" type="radio" ${
      ans.answer === 4 ? "checked" : ""
    } id="o-${i}-4" value="4" class="form-check mr-2" />
                                <label for="o-${i}-4" class="form-check-label">${
      questionsData[id].answer4
    }</label>
                            </div>
                            <div class="input-group review-container">
                              <input type="checkbox" id="r-${i}" class="review form-check" ${
      ans.review ? "checked" : ""
    } />
                              <label for="r-${i}" class="form-check-label">Review<label>
                            </div>
                            ${
                              i === 0
                                ? ""
                                : `<button class="btn btn-warning prevBtn" id="btn-${i}">Previous</button>`
                            }
                            ${
                              i === 49
                                ? '<button class="btn btn-danger submitBtn" id="submitBtn">Submit</button>'
                                : `<button class="btn btn-success nextBtn" id="btn-${i}">Next</button>`
                            }
                            
                        </div>
                `;
  });
  function submitAnswerSheet() {
    console.log("Answers");
    let a = 0;
    let u = 0;
    let r = 0;
    let ar = 0;
    answerSheet.map(({ answer, review }) => {
      if (answer === 0 && review === false) {
        u += 1;
      } else if (answer !== 0 && review === false) {
        a += 1;
      } else if (answer !== 0 && review === true) {
        ar += 1;
      } else if (answer === 0 && review === true) {
        r += 1;
      }
    });
    Swal.fire({
      title: "Are you sure?",
      html: `
        <div class="color-infos">
            <div class="d-flex align-items-center">
                <div id="" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-success d-flex justify-content-center align-items-center">${a}</div>
                <p class="m-0 ms-3">Attempted</p>
            </div>
            <div class="d-flex align-items-center">
                <div id="" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-primary d-flex justify-content-center align-items-center">${r}</div>
                <p class="m-0 ms-3">Review</p>
            </div>
            <div class="d-flex align-items-center">
                <div id="" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-info d-flex justify-content-center align-items-center">${ar}</div>
                <p class="m-0 ms-3">Attempted and Review</p>
            </div>
            <div class="d-flex align-items-center">
                <div id="" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-light text-dark d-flex justify-content-center align-items-center">${u}</div>
                <p class="m-0 ms-3">Unattempted</p>
            </div>
        </div>
      `,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Submit!",
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.removeItem("timeElapsed");
        // $(window).off("unload");
        // $(window).off("beforeunload");
        $.post(
          "answer_update.php",
          {
            type: "submit_exam",
            studentId: student_id,
            paperId: paper_id,
          },
          function (data, status, xhr) {
            localStorage.removeItem("timeElapsed");
            window.location = "index.php";
          }
        );
        localStorage.removeItem("timeElapsed");
      }
    });
  }
  function updateAnswerSheet(id) {
    let val = document.getElementById(`o-${id}-1`).checked
      ? 1
      : document.getElementById(`o-${id}-2`).checked
      ? 2
      : document.getElementById(`o-${id}-3`).checked
      ? 3
      : document.getElementById(`o-${id}-4`).checked
      ? 4
      : 0;
    let re = document.getElementById(`r-${id}`).checked;
    answerSheet[id].answer = val;
    answerSheet[id].review = re;
    generateAnswersTracker();
    $.post(
      "answer_update.php",
      {
        type: "answer_update",
        studentId: student_id,
        paperId: paper_id,
        answers: answerSheet,
      },
      function (data, status, xhr) {}
    );
  }
  $(".prevBtn").on("click", function (e) {
    // getPrevQuestion
    let id = e.target.getAttribute("id").split("-")[1];
    $(`#q${id}`).css("display", "none");
    $(`#q${parseInt(id) - 1}`).css("display", "block");
    updateAnswerSheet(id);
  });
  $(".nextBtn").on("click", function (e) {
    // getNextQuestion
    let id = e.target.getAttribute("id").split("-")[1];
    $(`#q${id}`).css("display", "none");
    $(`#q${parseInt(id) + 1}`).css("display", "block");

    updateAnswerSheet(id);
  });
  $("#submitBtn").on("click", function (e) {
    updateAnswerSheet(49);
    submitAnswerSheet();
  });

  // COUNTDOWN

  var timer2 = localStorage.getItem("timeElapsed")
    ? localStorage.getItem("timeElapsed")
    : $("#timeElapsed").text() !== ""
    ? $("#timeElapsed").text()
    : "50:00";
  var interval = setInterval(function () {
    var timer = timer2.split(":");
    //by parsing integer, I avoid all extra string processing
    var minutes = parseInt(timer[0], 10);
    var seconds = parseInt(timer[1], 10);
    --seconds;
    minutes = seconds < 0 ? --minutes : minutes;
    if (parseInt(minutes) === 0 && parseInt(seconds) === 0) {
      submitAnswerSheet();
      clearInterval(interval);
    }
    // if (minutes < 0) clearInterval(interval);
    seconds = seconds < 0 ? 59 : seconds;
    seconds = seconds < 10 ? "0" + seconds : seconds;
    //minutes = (minutes < 10) ?  minutes : minutes;
    $(".countdown").html(minutes + ":" + seconds);
    timer2 = minutes + ":" + seconds;
    timer3 = minutes + ":" + (parseInt(seconds) + 1).toString();
    localStorage.setItem("timeElapsed", timer3);
    $.post("answer_update.php", {
      type: "time_update",
      studentId: student_id,
      paperId: paper_id,
      timeElapsed: timer3,
    });
  }, 1000);

  /* GETTING VIDEO STREAM */
  let video = document.getElementById("cameraStream");
  navigator.mediaDevices
    .getUserMedia({
      video: true,
      audio: false,
    })
    .then(function (stream) {
      video.srcObject = stream;
      video.play();
    })
    .catch(function (err) {
      superErrorContainer.html("<div id='superError'></div>");
    });

  function takeASnap() {
    const canvas = document.createElement("canvas"); // create a canvas
    const ctx = canvas.getContext("2d"); // get its context
    canvas.width = video.videoWidth; // set its size to the one of the video
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0); // the video
    return new Promise((res, rej) => {
      canvas.toBlob(res, "image/jpeg"); // request a Blob from the canvas
    });
    // return canvas.toBlob(res, "image/jpeg");
  }

  function download(blob) {
    let a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    console.log("url", a.href);
    a.download = "screenshot.jpg";
    document.body.appendChild(a);
    a.click();
  }

  //**blob to dataURL**
  function blobToDataURL(blob, callback) {
    var a = new FileReader();
    a.onload = function (e) {
      callback(e.target.result);
    };
    a.readAsDataURL(blob);
  }

  console.log("started");
  setInterval(
    () => {
      newBlob = takeASnap().then((blob) => {
        blobToDataURL(blob, function (dataurl) {
          $.post("answer_update.php", {
            type: "post_image",
            studentId: student_id,
            paperId: paper_id,
            dataurl: dataurl,
          });
        });
      });
    },
    Math.floor(Math.random() * 12) * 10000 === 0
      ? 20000
      : Math.floor(Math.random() * 12) * 10000
  );
});
