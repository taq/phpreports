<?php
include "../database/db_array.php";

class DbArrayTest extends PHPUnit_Framework_TestCase {
	protected $interface;

	public function setUp(){
		$this->interface = new PHPReportsDBI();
	}

	public function testConnect(){
		$this->assertTrue($this->interface->db_connect(array()),"Could not connect.");
	}

	public function testQuery(){
		$data = array(	"id"=>array(1,2,3,4,5),
							"name"=>array("one","two","three","four","five"),
							"email"=>array("1@one","2@two","3@three","4@four","5@five"));
		$rtn = $this->interface->db_query($data,"");
		$this->assertEquals($this->interface->_array,$data,"Data is not the same.");
		$this->assertEquals($this->interface->_array,$rtn,"Returned data is not the same.");
		$this->assertEquals($this->interface->_keys,array("id","name","email"),"Wrong keys.");
		$this->assertEquals($this->interface->_pos,0,"Wrong initial position.");
		$this->assertEquals($this->interface->_cols,3,"Wrong number of cols.");
		$this->assertEquals($this->interface->_rows,5,"Wrong number of rows.");
		return $this->interface;
	}

	/**
	 * @depends testQuery
	 */
	public function testColNum(PHPReportsDBI $interface){
		$this->assertEquals($interface->db_colnum(null),3,"Wrong number of cols.");
	}

	/**
	 * @depends testQuery
	 */
	public function testColumnName(PHPReportsDBI $interface){
		$this->assertEquals($interface->db_columnName($null,1),"id");
		$this->assertEquals($interface->db_columnName($null,2),"name");
		$this->assertEquals($interface->db_columnName($null,3),"email");
	}

	/**
	 * @depends testQuery
	 */
	public function testColumnType(PHPReportsDBI $interface){
		$this->assertEquals($interface->db_columnType($null,1),"integer");
		$this->assertEquals($interface->db_columnType($null,2),"string");
		$this->assertEquals($interface->db_columnType($null,3),"string");
	}

	/**
	 * @depends testQuery
	 */
	public function testFetch(PHPReportsDBI $interface){
		$data = $interface->db_fetch(null);
		$this->assertEquals($data,array("id"=>1,"name"=>"one","email"=>"1@one"));
		$this->assertEquals($interface->_pos,1);

		$data = $interface->db_fetch(null);
		$this->assertEquals($data,array("id"=>2,"name"=>"two","email"=>"2@two"));
		$this->assertEquals($interface->_pos,2);

		$data = $interface->db_fetch(null);
		$this->assertEquals($data,array("id"=>3,"name"=>"three","email"=>"3@three"));
		$this->assertEquals($interface->_pos,3);

		$data = $interface->db_fetch(null);
		$this->assertEquals($data,array("id"=>4,"name"=>"four","email"=>"4@four"));
		$this->assertEquals($interface->_pos,4);

		$data = $interface->db_fetch(null);
		$this->assertEquals($data,array("id"=>5,"name"=>"five","email"=>"5@five"));
		$this->assertEquals($interface->_pos,5);

		$data = $interface->db_fetch(null);
		$this->assertNull($data);
		$this->assertEquals($interface->_pos,5);
	}
}
?>
