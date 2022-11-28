<style media="screen">
/* ID CARD STARTS HERE */
.id-card-holder {
  width: 225px;
  padding: 4px;
  margin: 0 auto;
  background-color: #1f1f1f;
  border-radius: 5px;
  position: relative;
  border: 1px solid #BDBDBD !important;
}
.id-card-holder:after {
  content: '';
  width: 7px;
  display: block;
  background-color: #0a0a0a;
  height: 100px;
  position: absolute;
  top: 105px;
  border-radius: 0 5px 5px 0;
}
.id-card-holder:before {
  content: '';
  width: 7px;
  display: block;
  background-color: #0a0a0a;
  height: 100px;
  position: absolute;
  top: 105px;
  left: 288px;
  border-radius: 5px 0 0 5px;
}
.id-card {
  background-color: #fff;
  padding: 10px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0 0 1.5px 0px #b9b9b9;
}
.id-card img {
  margin: 0 auto;
}
.header img {
  width: 100px;
  margin-top: 15px;
}
.photo img {
  width: 80px;
  margin-top: 15px;
}
h2 {
  font-size: 17px;
  margin: 8px 0;
}
h3 {
  font-size: 12px;
  margin: 4.5px 0;
  font-weight: 300;
}
.qr-code img {
  width: 50px;
}
p {
  font-size: 5px;
  margin: 2px;
}
.id-card-hook {
  background-color: #000;
  width: 70px;
  margin: 0 auto;
  height: 15px;
  border-radius: 5px 5px 0 0;
}
.id-card-hook:after {
  content: '';
  background-color: #d7d6d3;
  width: 47px;
  height: 6px;
  display: block;
  margin: 0px auto;
  position: relative;
  top: 6px;
  border-radius: 4px;
}
.id-card-tag-strip {
  width: 45px;
  height: 40px;
  background-color: #0950ef;
  margin: 0 auto;
  border-radius: 5px;
  position: relative;
  top: 9px;
  z-index: 1;
  border: 1px solid #0041ad;
}
.id-card-tag-strip:after {
  content: '';
  display: block;
  width: 100%;
  height: 1px;
  background-color: #c1c1c1;
  position: relative;
  top: 10px;
}
.id-card-tag {
  width: 0;
  height: 0;
  border-left: 100px solid transparent;
  border-right: 100px solid transparent;
  border-top: 100px solid #0958db;
  margin: -10px auto -30px auto;
}
.id-card-tag:after {
  content: '';
  display: block;
  width: 0;
  height: 0;
  border-left: 50px solid transparent;
  border-right: 50px solid transparent;
  border-top: 100px solid #d7d6d3;
  margin: -10px auto -30px auto;
  position: relative;
  top: -130px;
  left: -50px;
}
.school_title {
  font-size: 16px;
  margin-top: 5px;
  font-weight: 600;
}

.student_id_card > tbody > tr > td{
  padding: 0px !important;
}

.div-sc-one{
  width: 300px; 
  padding: 4px; 
  margin: 0 auto; 
  background-color: #1f1f1f; 
  border-radius: 5px; 
  position: relative; 
  border: 1px solid #BDBDBD !important;
}

.div-sc-two{
  background-color: #fff; 
  padding: 10px; 
  border-radius: 10px; 
  text-align: center; 
  box-shadow: 0 0 1.5px 0px #b9b9b9;
}

.im-sc-one{
  width: 100px; 
  margin-top: 15px;
}

.div-sc-three{
  text-align: center;
  font-size: 16px; 
  margin-top: 5px; 
  font-weight: 600;
}

.dv-sc-four{
  text-align: center;
}

.div-sc-five{
  width: 80px; 
  margin-top: 15px;
}

.head-sc-one{
  text-align: center; 
  font-size: 17px; 
  margin: 8px 0;
}

.table-sc-one{
  font-size: 11px; 
  text-align: center;
}

.td-sc-one{
  text-align: right; 
  width: 50%;
}

.td-sc-two{
  text-align: left;
}

.td-sc-three{
  text-align: right;
}

</style>

<!--title-->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <!-- ID CARD STARTS HERE -->
        <div class="id-card-hook"></div>
        <div class="id-card-holder div-sc-one" id="printableArea">
          <div class="id-card div-sc-two">
            <div class="dv-sc-four">
              <img class="im-sc-one" src="{{ asset('public/assets') }}/images/logo.png">
            </div>
            <div class="school_title div-sc-three">{{ DB::table('schools')->where('id', auth()->user()->school_id)->value('title') }}</div>
            <div class="photo div-sc-four">
              <img src="{{ $student_details->photo }}" class="rounded-circle div-sc-five">
            </div>
            <h2 class="head-sc-one">{{ $student_details->name }}</h2>
            <div class="dv-sc-four">
              <table class="student_id_card table-sc-one">
                <tbody>
                  <tr>
                    <td class="td-sc-one">{{ get_phrase('Code') }} : </td>
                    <td class="td-sc-two">{{ null_checker($student_details->code) }}</td>
                  </tr>
                  <tr>
                    <td class="td-sc-three">{{ get_phrase('Class') }} : </td>
                    <td class="td-sc-two">{{ null_checker($student_details->class_name) }}</td>
                  </tr>
                  <tr>
                    <td class="td-sc-three">{{ get_phrase('Section') }} : </td>
                    <td class="td-sc-two">{{ null_checker($student_details->section_name) }}</td>
                  </tr>
                  <tr>
                    <td class="td-sc-three">{{ get_phrase('Parent') }} : </td>
                    <td class="td-sc-two">{{ null_checker($student_details->parent_name) }}</td>
                  </tr>
                  <tr>
                    <td class="td-sc-three">{{ get_phrase('Blood group') }} : </td>
                    <td class="td-sc-two">{{ null_checker(strtoupper($student_details->blood_group)) }}</td>
                  </tr>
                  <tr>
                    <td class="td-sc-three">{{ get_phrase('Contact') }} : </td>
                    <td>{{ null_checker($student_details->phone) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <hr>
            
            </div>
          </div>
          <!-- ID CARD ENDS HERE -->

          <div class="d-print-none mt-4">
            <div class="text-center">
              <input type="button" class="btn btn-primary" onclick="printableDiv('printableArea')" value="{{ get_phrase('Print') }}" />
            </div>
          </div>
          <!-- end buttons -->

        </div> <!-- end card-body-->
      </div> <!-- end card -->
    </div> <!-- end col-->
  </div>

<script type="text/javascript">
  
  "use strict";

  function printableDiv(printableAreaDivId) {
    var printContents = document.getElementById(printableAreaDivId).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
  }
</script>
