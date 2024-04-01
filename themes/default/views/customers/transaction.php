<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?= lang('update_info'); ?></h3>
				</div>
				<div class="box-body">
          <?php echo form_open("customers/transaction/".$customer->id);?>
					<div class="col-md-12">
							<h4 class="box-title"><b>Personal Information</b></h4>
						</div>
					<div class="col-md-6">
					 <div class="form-group">
							<label class="control-label" for="code">Products</label>
							<select class="form-control select2" name="lifetime" >
								<?php foreach ($product as $item): ?>
									<option value="<?= $item->lifetime; ?>" ><?= $item->name; ?></option>
								<?php endforeach; ?>
							</select>
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
							<?= form_input('name', set_value('name', $customer->name), 'readonly class="form-control input-sm" id="name"'); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
						<?php echo form_submit('edit_customer', 'Edit Transaction', 'class="btn btn-primary"');?>
						</div>
					</div>	
                        

					
					</div>

					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</section>
