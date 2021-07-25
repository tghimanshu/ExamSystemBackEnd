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
  let student_id = $("#student_id").text();
  let paper_id = $("#paper_id").text();
  console.log($("#answers").text());
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
  console.log(answerSheet);

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
                            <h5>${i + 1}. ${questionsData[id].question}</h5>
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
    localStorage.removeItem("timeElapsed");
    $.post(
      "answer_update.php",
      {
        type: "submit_exam",
        studentId: student_id,
        paperId: paper_id,
      },
      function (data, status, xhr) {
        alert("exam submitted");
        localStorage.removeItem("timeElapsed");
        window.location = "index.php";
      }
    );
    localStorage.removeItem("timeElapsed");
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
    : "5:00";
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
});

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
    console.log("An error occurred: " + err);
  });
