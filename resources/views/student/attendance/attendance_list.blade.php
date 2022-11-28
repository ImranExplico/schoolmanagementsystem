<?php 

use App\Http\Controllers\CommonController;

use App\Models\Classes;
use App\Models\Section;
use App\Models\DailyAttendances;
use App\Models\Session;

$class = Classes::find($page_data['class_id']);
$section = Section::find($page_data['section_id']);
$active_session = Session::where('status', 1)->first();
$attendance_of_students = DailyAttendances::where(['class_id' => $page_data['class_id'], 'section_id' => $page_data['section_id'], 'student_id' => auth()->user()->id])->get();

?>

<?php if(count($attendance_of_students) != 0): ?>
	<div class="attendence_sheet" id="attendence_report">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<div class="card bg-secondary text-white">
					<div class="card-body">
						<div class="text-center">
							<h4>{{ get_phrase('Attendance report').' '.get_phrase('Of').' '.date('F', $page_data['attendance_date']) }}</h4>
							<h5>{{ get_phrase('Class') }} : {{ $class->name }}</h5>
							<h5>{{ get_phrase('Section') }} : {{ $section->name }}</h5>
							<h5>
								{{ get_phrase('Last updated at') }} :
								<?php if ($attendance_of_students[0]->updated_at == ""): ?>
								{{ get_phrase('not_updated_yet') }}
								<?php else: ?>
								{{ date('d-M-Y', strtotime($attendance_of_students[0]->updated_at)) }} <br>
								{{ get_phrase('Time') }} : {{ date('h:i:s', strtotime($attendance_of_students[0]->updated_at)) }}
								<?php endif; ?>
							</h5>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4"></div>
		</div>
		<div class="table-responsive">
			<table  class="table table-bordered table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-sm">
				<thead class="thead-dark">
					<tr>
						<th width="40px">{{ get_phrase('Student') }} <i class="mdi mdi-arrow-down"></i> {{ get_phrase('Date') }} <i class="mdi mdi-arrow-right"></i></th>
						<?php
						$number_of_days = date('m', $page_data['attendance_date']) == 2 ? (date('Y', $page_data['attendance_date']) % 4 ? 28 : (date('m', $page_data['attendance_date']) % 100 ? 29 : (date('m', $page_data['attendance_date']) % 400 ? 28 : 29))) : ((date('m', $page_data['attendance_date']) - 1) % 7 % 2 ? 30 : 31);
						for ($i = 1; $i <= $number_of_days; $i++): ?>
							<th>{{ $i }}</th>
						<?php endfor; ?>
					</tr>
				</thead>
				<tbody>
					<?php
	                $student_id_count = 0;
	                foreach($attendance_of_students as $attendance_of_student){ ?>
	                  <?php $student_details = (new CommonController)->get_student_details_by_id($attendance_of_student['student_id']); ?>
	                  <?php if(date('m', $page_data['attendance_date']) == date('m', $attendance_of_student['timestamp'])): ?>
	                    <?php if($student_id_count != $attendance_of_student['student_id']): ?>
	                      <tr>
	                        <td>{{ $student_details->name }}</td>
	                        <?php for ($i = 1; $i <= $number_of_days; $i++): ?>
	                          <?php $page_data['date'] = $i.' '.$page_data['month'].' '.$page_data['year']; ?>
	                          <?php $start_timestamp = strtotime($page_data['date']); ?>
	                          <?php $end_timestamp = $start_timestamp + 86400; ?>
	                          <td class="text-center">
	                            <?php 
	                            $attendance_by_id = DailyAttendances::where('class_id', $page_data['class_id'])
	                            ->where('section_id', $page_data['section_id'])
	                            ->where('student_id', $attendance_of_student['student_id'])
	                            ->where('school_id', auth()->user()->school_id)
	                            ->where('timestamp', '>', $start_timestamp)
	                            ->where('timestamp', '<', $end_timestamp)
	                            ->first();
	                            ?>
	                            <?php if(isset($attendance_by_id->status) && $attendance_by_id->status == 1){ ?>
	                              <i class="bi bi-circle-fill text-success"></i>
	                            <?php }elseif(isset($attendance_by_id->status) && $attendance_by_id->status == 0){ ?>
	                              <i class="bi bi-circle-fill text-danger"></i>
	                            <?php } ?>
	                          </td>
	                        <?php endfor; ?>
	                      </tr>
	                    <?php endif; ?>
	                    <?php $student_id_count = $attendance_of_student['student_id']; ?>
	                  <?php endif; ?>
	                <?php } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php else: ?>
	<div class="empty_box center">
		<img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" />
		<br>
		<span class="">{{ get_phrase('No data found') }}</span>
	</div>
<?php endif; ?>