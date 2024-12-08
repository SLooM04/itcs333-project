<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coin</title>
    <style>
    /*----------------COIN------------------*/
    body {
  transform: scale(0.7); /* Scale the content to 70% of its original size */
  transform-origin: 0 0; /* Keeps the scaling from the top-left corner */
  width: 142.86%; /* Adjust to ensure content fits properly */
  height: 142.86%; /* Adjust to ensure content fits properly */
  padding-top: 80%; /* Adjusted padding to accommodate content scaling */
}

span {
  font-family: "Montserrat", sans-serif;
}

.jump {
  animation: jump 1.5s infinite ease;
}

@keyframes jump {
  0% {
    top: 0;
  }
  50% {
    top: -40px;
  }
  100% {
    top: 0;
  }
}

.coin {
  margin: auto;
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  top: 0;
  height: 150px;
  width: 150px;
}

.coin .front, .coin .back {
  position: absolute;
  height: 150px;
  width: 150px;
  background: #929292; /* Gray background */
  border-radius: 50%;
  border-top: 7px solid #a9a9a9; /* Light gray border */
  border-left: 7px solid #a9a9a9;
  border-right: 7px solid #696969; /* Darker gray border */
  border-bottom: 7px solid #696969;
  transform: rotate(44deg);
}

.coin .front:before, .coin .back:before {
  content: "";
  margin: 35.5px 35.5px;
  position: absolute;
  width: 70px;
  height: 70px;
  background: #b0b0b0; /* Lighter gray */
  border-radius: 50%;
  border-bottom: 5px solid #a9a9a9;
  border-right: 5px solid #a9a9a9;
  border-left: 5px solid #696969;
  border-top: 5px solid #696969;
  z-index: 2;
}

.coin .front .currency, .coin .back .currency {
  overflow: hidden;
  position: absolute;
  color: #d4d4d4; /* Light gray color */
  margin-top: 5px;
  margin-left: 7px;
  font-size: 35px;
  transform: rotate(-44deg);
  line-height: 3.7;
  width: 100%;
  height: 100%;
  text-align: center;
  text-shadow: 0 3px 0 #606468; /* Light shadow for contrast */
  z-index: 3;
  font-weight: bold;
  border-radius: 50%;
}

.coin .front .currency:after, .coin .back .currency:after {
  content: "";
  position: absolute;
  height: 200px;
  width: 40px;
  margin: 20px -65px;
  box-shadow: 50px -23px 0 -10px rgba(255, 255, 255, 0.22), 85px -10px 0 -16px rgba(255, 255, 255, 0.19);
  transform: rotate(-50deg);
  animation: shine 1.5s infinite ease;
}

@keyframes shine {
  0% {
    margin: 20px -65px;
  }
  50% {
    margin: 70px -85px;
  }
  100% {
    margin: 20px -65px;
  }
}

.coin .front .shapes, .coin .back .shapes {
  transform: rotate(-44deg);
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.coin .front .shapes div, .coin .back .shapes div {
  width: 20px;
  height: 4px;
  background: #696969; /* Darker gray for shapes */
  border-top: 2px solid #808080; /* Lighter gray border */
  margin: 75px 7px;
}

.coin .front .shapes div:before, .coin .back .shapes div:before {
  content: "";
  position: absolute;
  width: 20px;
  height: 4px;
  background: #696969; /* Darker gray */
  border-top: 2px solid #808080;
  margin: -10px 0;
}

.coin .front .shapes div:after, .coin .back .shapes div:after {
  content: "";
  position: absolute;
  width: 20px;
  height: 4px;
  background: #696969; /* Darker gray */
  border-top: 2px solid #808080;
  margin: 8px 0;
}

.coin .front .shape_l, .coin .back .shape_l {
  float: left;
}

.coin .front .shape_r, .coin .back .shape_r {
  float: right;
}

.coin .front .top, .coin .back .top {
  font-size: 25px;
  margin-top: 10px;
  color: #c0c0c0; /* Gray text */
  text-align: center;
  font-weight: bold;
  width: 100%;
  position: absolute;
  left: 0;
}

.coin .front .bottom, .coin .back .bottom {
  font-size: 23px;
  color: #c0c0c0; /* Gray text */
  text-align: center;
  font-weight: bold;
  width: 100%;
  position: absolute;
  left: 0;
  bottom: 0;
}



@keyframes swift {
  0% {
    opacity: 0.8;
  }
  50% {
    opacity: 0.4;
    transform: scale(0.8);
  }
  100% {
    opacity: 0.8;
  }
}


    </style>
</head>
<body><div class='coin'>
  <div class='front jump'>
    <div class='star'></div>
    <span class='currency'>UOB</span>
    <div class='shapes'>
      <div class='shape_l'></div>
      <div class='shape_r'></div>
      <span class='top'>booking</span>
      <span class='bottom'>coin</span>
    </div>
  </div>
  <div class='shadow'></div>
</div>

</body>
</html>
