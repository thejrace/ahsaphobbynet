<?php

	class Pagination {

		protected $currentPage, $recordCount, $totalPage, $from, $to;

		public function get_from(){
			return $this->from;
		}

		public function get_to(){
			return $this->to;
		}

		protected function calculate( $recordCount, $settings ){

			$this->currentPage =  $settings["page"];
			$this->recordCount = $recordCount;

			$this->totalPage   = ceil( $this->recordCount / $settings["rrp"] );
			$this->from     = ( $this->currentPage * $settings["rrp"] ) - $settings["rrp"];		
			if ( ( $this->currentPage * $settings["rrp"] ) > $this->recordCount ) {
				$this->to = $this->recordCount;
			} else {
				$this->to = $this->currentPage * $settings["rrp"];
			}
		}

		public function create( $recordCount, $settings ){

			$this->calculate( $recordCount, $settings );
			$sayfaOptions = "";
			$rrpOptions = "";
			for ( $i = 1; $i <= $this->totalPage; $i++ ){
				$selected = '';
				if( $settings["page"] == $i ) $selected = 'selected'; 
				$sayfaOptions .= '<option '.$selected.'>'.$i.'</option>';
			}

			// Gosterim sekilleri bunlar
			$rrpArray = [5, 6, 10, 30, 50, 100];

			foreach( $rrpArray as $rrp ){
				
				$selected = '';
				if( $rrp == $settings["rrp"] ) $selected = 'selected';

				$rrpOptions .= '<option '.$selected.'>'.$rrp.'</option>';
			}		
			( $settings["page"] - 1 != 0 ) ? $btnPrev = ( $settings["page"] - 1 ) : $btnPrev = $this->totalPage;
			( $settings["page"] < $this->totalPage ) ? $btnNext = ( $settings["page"] + 1 ) : $btnNext = 1;
			
			return '
						<div class="pagination-col">
							<span>Kayıt Sayısı</span>

							<select name="dt_rrp" id="dt_rrp" class="selectSayfalama" onchange=\'DT_functions.change_rrp(this.value);\' >
								'.$rrpOptions.'
							</select>
						</div>

						<div class="pagination-col">
							<button  class="btnSayfalama solSonOk"  onclick=\'DT_functions.change_page(1);\'></button>
							<button  class="btnSayfalama solOk" onclick=\'DT_functions.change_page('.$btnPrev.');\'></button>
						</div>

						<div class="pagination-col">
							<span>Sayfa</span>
							<select name="dt_page" id="dt_page" class="selectSayfalama" onchange=\'DT_functions.change_page(this.value);\' >
								'.$sayfaOptions.'
							</select>
						</div>
						
						<div class="pagination-col">
							<span>( '.$this->from.' - '.$this->to.' / '.( $this->recordCount ).' )</span>
						</div>

						<div class="pagination-col">
							<button class="btnSayfalama sagOk" onclick=\'DT_functions.change_page('.$btnNext.'); \'></button>
							<button class="btnSayfalama sagSonOk" onclick=\'DT_functions.change_page('.$this->totalPage.');\'></button>
						</div>';
		}

		public function create_site_template( $recordCount, $settings ){
			$this->calculate( $recordCount, $settings );

			$html = "";
			if( $this->recordCount != 0 ){

				$from = $this->from;
				if( $this->from == 0 ) $from = 1;
				
				$html = '
						<div class="sayfalama-info">
							<span> Bu kategoride <b>'.( $this->recordCount ).'</b> adet ürün bulunmuştur. <b>'.$this->currentPage.'.</b> sayfa görüntüleniyor. </span>
						</div>

						<div class="sayfalama-butonlar">
						<a href="" class="btn sayfa-ilk" onclick="DT_functions.change_page(1); return false;" ></a>
						';

				for( $i = 1; $i <= $this->totalPage; $i++ ){
					$selected = '';
					if( $this->currentPage == $i ) {
						$selected = 'selected'; 
					} 
					$html .= '
						<a href="" class="'.$selected.'" onclick="DT_functions.change_page('.$i.'); return false;"> '. $i .' </a>
					';
				}	

				$html .=
					'<a href="" class="btn sayfa-son" onclick="DT_functions.change_page('.$this->totalPage.'); return false;" ></a>


					</div>

					';
			} else {
				$html = "";
			}

		return $html;

	}

}