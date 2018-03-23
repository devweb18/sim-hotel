<html>
<style type="text/css">

body {
    margin: 0.1in;
}
#kiri {
    width: 20%;
    float: left;
    padding: 10px;
}
#kanan {
    width: 80%;
}

h1, h2, h3, h4, h5, h6, li, blockquote, p, th, td {
    font-family: Helvetica, Arial, Verdana, sans-serif; /*Trebuchet MS,*/
}
h1, h2, h3, h4 {
    color: #000000;
    font-weight: normal;
}
h4, h5, h6 {
    color: #000000;
}
h2 {
    margin: 0 auto auto auto;
    font-size: x-large;
}
li, blockquote, p, th, td {
    font-size: 80%;
}
ul {
    list-style: url(/img/bullet.gif) none;
}

#footer {
    border-top: 1px solid #000000;
    text-align: right;
}

table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    font-family: "Trebuchet MS", Arial, sans-serif;
    color: black;    
}
td, th {
    padding: 4px;
}

P.breakhere {page-break-after: always}
</style>
 <body>
    <div id="kiri">
        <img align="left" src="<?php echo base_url();?>images/<?=$logo?>.png" width="150"  height="100"/>
        </div>
        
   <div id="kanan" class="page-header">
   <br/>
        <h3 align="center">GUEST BILL</h3>
        <h4 align="center"><?=$namapt?></h4>
        </div>
        
    <br/>
    <hr/>

    <hr/>
 <table border="1" width="100%" >
	<tr>
		<th colspan="9" align="left"><img align="left" src="<?php echo base_url();?>images/<?=$s?>.png" width="150" /><h4><b></b></h4></th>
	</tr>
	<tr>
		<th class="warning" align="left">Nota.no</th>
		<th colspan="8" align="left"><?=$depo?>000<?=$h_row->nota?></th>
	</tr>
	<tr>
		<th class="warning" align="left">Name</th>
		<th colspan="8" align="left"><?=$h_row->nama?></th>
	</tr>
	<tr>
		<td class="warning">Address</td>
		<td colspan="8"><?=$h_row->alamat?></td>
	</tr>
	<tr>
		<td class="warning">Check in</td>
		<td class="success">date</td>
		<td><?=substr($h_row->cekin,'0','-6')?></td>
		<td class="success">time</td>
		<td colspan="2"><?=substr($h_row->cekin,'11')?></td>
		<td colspan="3"></td>
	</tr>
	
	<tr>
		<td class="warning">Check out</td>
		<td class="success">date</td>
		<td><?=substr($h_row->cekout,'0','-6')?></td>
		<td class="success">time</td>
		<td colspan="2"><?=substr($h_row->cekout,'11')?></td>
		<td colspan="3"></td>

	</tr>
	<tr>
		<td colspan="9"></td>
	</tr>
	<tr>
		<td colspan="6" align="left">
			<table border="1" width="100%">

	<tr>
		<td></td>
		<td class="warning">Date</td>
		<td class="warning">Trnsfr (Rp)</td>
		<td class="warning">Cash (Rp)</td>
	</tr>
	
	
	<?php 
	//$d=$this->db->get_where('tbl_deposit',array('id_p'=>$id_p));
	$d=$this->Mhotel->get_all_deposit($id_p);
	$no=1;
	foreach($d->result() as $dep){ 
	$jml = $dep->cas + $dep->transfer;
	if ($jml != 0 ){
	?>
		<tr>
		<td class="success">Deposit <?=$no++?></td>
		<td><?=$dep->tanggal?></td>
		<td> <p align="right"><?=number_format($dep->transfer,0,',','.')?>
			
			
		</p>
		</td>
		<td ><p align="right"><?php if(!empty($dep->cas)){
			echo number_format($dep->cas,0,',','.');
		} ?>
		
			</p>
		</td>
	</tr>
<?php	}} ?>
	
	
	<tr>
		<td colspan="4"></td>
	</tr>
	</table>
		</td>
		<td colspan="3" >
			<table border="1" width="100%" >
<?php 
			//$dtota=0;
			$cas0=0;
			$trans0=0;
			$dt=$this->db->get_where('tbl_deposit',array('id_p'=>$id_p));
			foreach($dt->result() as $dto){ 
			  $cas0=$cas0+$dto->cas;
			 $trans0=$trans0+$dto->transfer;
			
			 //$cas0;
			} 
			 $totd=$cas0+$trans0;
			 if($h_row->disc=='active'){
				 $disc=(5*$totdisc)/100;
				 $active='Non active';
				$span='remove-sign';
				$hrf='NULL';
			}else{
				$active='active';
				$span='ok-circle';
				$hrf='active';
				$disc=0;
			}
			
			$payment=$p-$disc;
			$totalall=$totd-$payment;
			if($totd>=$payment){
				$wt='success';
				//$this->Mhotel->lunas($id_p,'Lunas');
			}else{
				$wt='danger';
				//$this->Mhotel->lunas($id_p,'Tagihan');
			}
			
			 ?>
	<tr>
		<td class="success" >Amount</td>
		<td align="right"><?=number_format($p,0,',','.')?></td>
	</tr>
	<tr>
		<td class="success" >Disc <span class="pull-right"></span></td>
		<td align="right"><?=number_format($disc,0,',','.')?></td>
	</tr>
	<tr>
		<td class="success" >Temporary Payment </td>
		<td  align="right"><?php
		$payment = $this->Mhotel->temporary_payment($id_p);
		echo number_format($payment,0,',','.')
		?></td>
	</tr>
	<tr>
		<td class="success" >Deposit</td>
		<td align="right"  >
			
			<?php 
			$totd = $this->Mhotel->total_deposit($id_p);
			echo number_format($totd,0,',','.');
			?>
		</td>
	</tr>
	<tr>
		<td class="warning">Total Payment</td>
		<td  align="right" class="<?=$wt?>"><?=number_format($totalall,0,',','.')?></td>
	</tr>
	
	</table>
		</td>
	</tr>
	<tr>
		<td colspan="9">
			
		</td>
	</tr>
	
	<tr>
		<td colspan="9">
			<table border="1" width="100%">
	<tr class="success">
		<td align="center" colspan="7">Room, Bed, & OT</td>
	</tr>
	<tr class="warning">
		<td>Date</td>
		<td>Room.no</td>
		<td>Bed / day</td>
		<td>OT / hours</td>
		<td>Amount</td>
		<td>Balance</td>

	</tr>
	<?php
	$tot=0;
	foreach($h->result() as $hh){ 
	//$amm = $this->db->get_where('tbl_pesan_kamar',array('id'=>$hh->id_k));
	$am = $this->db->get_where('tbl_kamar',array('id_kamar'=>$hh->id_k))->row();
	if($hh->ot<4){
		$ot=$am->ot;
	}else{
		$ot=$am->ot2;
	}
	?>
		<tr>
		<td ><?=$hh->tanggal?></td>
		<td><?=$hh->id_k?></td>
		<td>
		<?=!empty($hh->bed) ?  $hh->bed .'.( 60000 )':''?>
  		</td>
		
		<td>
		<?=!empty($hh->ot) ?  $hh->ot.'.( '.$ot.' )' :''?>
  		</td>
				
		<td align="right"><?=number_format($am->harga,0,',','.');
		$tot=$tot+$am->harga+$hh->harga_bed+$hh->harga_ot;
		?></td><!--penjumlahan ber.ulang-->
		<td  align="right"><?=number_format($tot,0,',','.');?></td>
		</tr>
<?php	 }
	 ?>
	
	
  </table>
			
		</td>
		
	</tr>
	<tr>
		<td colspan="9"></td>
	</tr>
	<tr>
		<td colspan="9">
			 <table border="1" width="100%">
	<tr class="success">
		<td align="center" colspan="4">Food, Beverage, Laundry, & Etc</td>
	</tr>
	<tr class="warning">
		<td>Date</td>
		<td>Note.no</td>
		<td>Amount</td>
		<td>Balance</td>

	</tr>
	<?php $tott=0;	foreach($h1->result() as $hh1){ ?>
	           <tr>
		<td ><?php echo $hh1->tanggal?></td>
		<td><?=$hh1->nota?></td>
		<td  align="right"><?=number_format($hh1->harga,0,',','.');
		$tott=$tott+$hh1->harga;
		?></td>
		<td  align="right"><?=number_format($tott,0,',','.');?></td>
	</tr>
<?php	}?>
	
	
  </table>
		</td>
	</tr>
	</table>
<br/>

<br/>
  
</body>
</html>