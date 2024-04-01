<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?= lang('update_info'); ?></h3>
				</div>
				<div class="box-body">
          <?php echo form_open("customers/edit/".$customer->id);?>
					<div class="col-md-12">
							<h4 class="box-title"><b>Personal Information</b></h4>
						</div>
					<div class="col-md-6">
						<!-- <div class="form-group">
							<label class="control-label" for="code">Members Code</label>
							<?= form_input('name', set_value('name'), 'class="form-control input-sm" id="name"'); ?>
						</div> -->

						<div class="form-group">
							<label class="control-label" for="email_address">Identity Type</label>
							<select class="form-control" name="idcard" id="idcard">
								<option value="<?php echo $customer->idcard; ?>"><?php echo $customer->idcard; ?></option>
								<option value="ktp">KTP</option>
								<option value="sim">SIM</option>
								<option value="kp">Kartu Pelajar</option>
								<option value="km">Kartu Mahasiswa</option>
								<option value="passpoprt">Passport</option>
								<option value="others">Others</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label" for="email_address">Occupation</label>
							<select class="form-control" name="occupation">
								<option value="<?php echo $customer->occupation; ?>"><?php echo $customer->occupation; ?></option>
								<option value="Swasta">Swasta</option>
								<option value="Wiraswasta">Wiraswasta</option>
								<option value="TNI/POLRI">TNI/POLRI</option>
								<option value="Pelajar">Pelajar</option>
								<option value="Mahasiswa">Mahasiswa</option>
								<option value="Guru">Guru / Dosen</option>
							</select>
						</div>

						
						<div class="form-group">
							<label class="control-label" for="email_address">Identity ID</label>
							<?= form_input('no_id', set_value('no_id', $customer->no_id), 'class="form-control input-sm" '); ?>
						</div>
						<div class="form-group">
							<label class="control-label" for="email_address">Phone Number</label>
							<?= form_input('phone', set_value('phone',$customer->phone), 'class="form-control input-sm" id="phone"'); ?>
						</div>
					
						<div class="form-group">
							<label class="control-label" for="email_address">Start Date</label>
							<!-- <input type="text" class="form-control pull-right" id="datepicker"> -->
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right" name="start_date" value="<?php echo date('m/d/Y', strtotime($customer->start_date)); ?>" id="datepicker2" required>
								</div>
						</div>
						

					
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="email_address">Full Name</label>
							<?= form_input('name', set_value('name', $customer->name), 'class="form-control input-sm" id="name"'); ?>
						</div>
						<div class="form-group">
							<label class="control-label" for="sex">Sex</label>
							<select class="form-control" name="sex">
								<option value="<?php echo $customer->sex; ?>"><?php echo $customer->sex; ?></option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label" for="email_address">Email</label>
							<?= form_input('email', set_value('email', $customer->email), 'class="form-control input-sm" id="email_address"'); ?>
						</div>
						<div class="form-group">
						<label class="control-label" for="email_address">Place of Birth</label>
							<?= form_input('place', set_value('place', $customer->place), 'class="form-control input-sm" id="place"'); ?>
						</div>

						<!-- <div class="form-group">
						<label class="control-label" for="email_address">Photo</label>
							<input type="file" name="userfile" id="image" class="form-control">
						</div> -->
					</div>


							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label" for="email_address">Address </label>
									<!-- <textarea class="form-control" name="address" rows="3" value="<?php echo $customer->address; ?>"></textarea> -->
                  <?= form_textarea('address', set_value('address', $customer->address), '  rows="3" class="form-control " id="address"'); ?>
								</div>
							</div>






					<div class="col-md-12">
							<h4 class="box-title"><b>Contact Information</b></h4>
						</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="code">Emergency Person</label>
							<?= form_input('emergency_person', set_value('emergency_person', $customer->emergency_person), 'class="form-control input-sm" id="emergency_name"'); ?>
						</div>

						

						<div class="form-group">
            <?php echo form_submit('edit_customer', 'Edit Member', 'class="btn btn-primary"');?>
						</div>
						
					</div>
					<div class="col-md-6">
					<div class="form-group">
							<label class="control-label" for="email_address">Emergency Number</label>
							<?= form_input('emergency_number', set_value('emergency_number', $customer->emergency_number), 'class="form-control input-sm" id="emergency_number"'); ?>
						</div>
					</div>

					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</section>
