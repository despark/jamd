<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Tests for Jam_Builder SELECT functionality with Paranoid Tests.
 *
 * @package Jam
 * @group   jam
 * @group   jam.behavior
 * @group   jam.behavior.paranoid
 */
class Jam_Behavior_ParanoidTest extends Testcase_Database {

	
	public function test_select()
	{
		$this->assertNotNull(Jam::find('test_video', 1), 'Should be able to find not deleted videos');
		$this->assertNull(Jam::find('test_video', 3), 'Should not be able to find deleted videos');

		$this->assertCount(4, Jam::all('test_video'));
		$this->assertCount(5, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::ALL));
		$this->assertCount(1, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::DELETED));
	}

	public function test_set()
	{
		$video = Jam::find('test_video', 1);

		$video->delete();

		$this->assertCount(3, Jam::all('test_video'));
		$this->assertCount(5, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::ALL));
		$this->assertCount(2, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::DELETED));

		$video = Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::ALL)->where(':primary_key', '=', 1)->first();

		$video->restore_delete();

		$this->assertCount(4, Jam::all('test_video'));
		$this->assertCount(5, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::ALL));
		$this->assertCount(1, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::DELETED));

		$video->real_delete();

		$this->assertCount(3, Jam::all('test_video'));
		$this->assertCount(4, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::ALL));
		$this->assertCount(1, Jam::all('test_video')->deleted(Jam_Behavior_Paranoid::DELETED));
	}
}