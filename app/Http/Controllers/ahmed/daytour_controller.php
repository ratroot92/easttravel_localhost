<?php

namespace App\Http\Controllers\ahmed;

use App;
use App\Category;
use App\City;
use App\Country;
use App\Daytour;
use App\File;
use App\Http\Controllers\Controller;
use App\Image;
use App\Package_Icon;
use DB;
use Illuminate\Http\Request;
use PDF;
use Validator;

class daytour_controller extends Controller {

	private $base_url;

	public function __construct() {

		$this->base_url = url('/');

	}
	public function view() {

		$daytours = Daytour::with('getcity')->get();

		return view('daytours/index', [
			'daytours' => $daytours,

		]);
	}

	public function add() {
		$all_categories = DB::table('package_cat')
			->where('class', 'daytour')
			->get();

		// $city_list=DB::table('city_list')
		//                   ->get();

		$country_list = DB::table('country_list')
			->get();

		$icons = DB::table('icons')
			->get();

		return view('daytours/add', [
			'packagecat' => $all_categories,
			'icons' => $icons,
			'country_list' => $country_list,
		]);
	}

	//    public function cityByCountry($country){

	//    // $country_selected = DB::table('country_list')
	//    //                   ->where('name',$country)
	//    //                   ->first();

	//   $city_list         = DB::table('city_list')
	//                      ->join('states', 'states.id', '=', 'city_list.state_id')
	//                      ->join('country_list', 'country_list.id', '=', 'states.country_id')
	//                      ->where('country_list.id', '=', $country)
	//                      ->select('city_list.name')
	//                      ->get();
	// //dd($city_list);

	// //$html                 = view('daytours/city',compact('city_list'))->render();
	// return                 response()->json($city_list);

	//     }

	public function insert(Request $request) {
		$validator = Validator::make($request->all(), [

			// 'name'                    =>'required',
			// 'city'                 =>'required',
			// 'country'                 =>'required',
			// 'cat'                  =>'required',
			// 'desc'                    =>'required',
			// 'file'                    =>'required',
			// 'img'                     =>'required',
			// 'about'                   =>'required',
			// 'day_detail'              =>'required',
			// 'duration'                =>'required',
			// 'disc'                    =>'required',
			// 'date'                    =>'required',
			// 'price'                   =>'required',
			// 'code'                    =>'required',
		]);

		if ($validator->fails()) {
			return redirect('/daytour/add')
				->withErrors($validator)
				->withInput();

		} else {
			$input = $request->all();

			$first_file = '';
			$first_img = '';
			$last_activity_id = 0;
			$last_activity = Daytour::orderBy('created_at', 'desc')->first();
			if ($last_activity != null) {
				$last_activity_id = $last_activity->id + 1;
			} else {
				$last_activity_id = 20001;
			}

			if ($request->hasFile('img')) {

				$images = $request->file('img');

				foreach ($images as $img) {

					$extension = $img->getClientOriginalExtension();
					$image_name = time() . rand(1000, 9999) . "_." . $extension;
					$path = public_path('/storage/daytour_images/');
					$img->move($path, $image_name);
					$img_path = $this->base_url . '/public/storage/daytour_images/' . $image_name;

					$newimage = new Image;
					$newimage->fkey = $last_activity_id;
					$newimage->src = $img_path;
					$newimage->name = $image_name;
					$newimage->save();

				}

				$first_img = DB::table('images')
					->where('fkey', $last_activity_id)
					->first();

			}

			if ($request->hasFile('file')) {

				$files = $request->file('file');

				if (count($files) > 0) {

					foreach ($files as $file) {
						$extension = $file->getClientOriginalExtension();
						$file_name = time() . rand(1000, 9999) . "_." . $extension;
						$path = public_path('/storage/daytour_files/');
						$file->move($path, $file_name);
						$file_path = $this->base_url . '/public/storage/daytour_files/' . $file_name;

						$newimage = new File;
						$newimage->fkey = $last_activity_id;
						$newimage->src = $file_path;
						$newimage->name = $file_name;
						$newimage->save();
					}

				}

				// $first_file=DB::table('files')
				// ->where('fkey',$last_activity_id)
				// ->first();

			}

			$new_activity = new Daytour;
			$new_activity->id = $last_activity_id;
			$new_activity->name = $request->input('name');
			//$new_activity->city=$request->input('city');
			//$new_activity->country=$country;
			/*  $new_activity->cat=$request->input('cat');*/
			$new_activity->desc = $request->input('desc');
			if ($first_img) {
				$new_activity->banner = $first_img->src;
				$new_activity->img = $first_img->src;
			}
			$new_activity->about = $request->input('about');
			$new_activity->day_detail = $request->input('day_detail');
			$new_activity->duration = $request->input('duration');
			$new_activity->disc = $request->input('disc');
			$new_activity->price = $request->input('price');
			$new_activity->code = $request->input('code');
			$new_activity->terms = $request->input('terms');
			$new_activity->payment_policy = $request->input('payment_policy');
			$new_activity->payment_methods = $request->input('payment_methods');
			$new_activity->cancellation_policy = $request->input('cancellation_policy');
			$new_activity->visa_info = $request->input('visa_info');
			$new_activity->notes = $request->input('notes');
			$new_activity->questions = $request->input('questions');
			$new_activity->grpsize = $request->input('grpsize');
			$new_activity->tourcode = $request->input('tourcode');
			$new_activity->destinations = $request->input('destinations');
			$new_activity->startloc = $request->input('startloc');
			$new_activity->tourstyle = $request->input('tourstyle');
			$new_activity->tourlanguage = $request->input('tourlanguage');
			$new_activity->avalibilitydetails = $request->input('avalibilitydetails');
			$new_activity->tranportdetails = $request->input('tranportdetails');
			$new_activity->accomodationdetails = $request->input('accomodationdetails');
			$new_activity->mealdetails = $request->input('mealdetails');
			$new_activity->guidedetails = $request->input('guidedetails');
			$new_activity->status = $request->input('status');
			$new_activity->save();
			if ($new_activity) {

				$countries = $request->input('country');
				if (count($countries) > 0) {

					foreach ($countries as $country) {
						Country::create([
							'fkey' => $last_activity_id,
							'name' => $country,
							'of' => "daytour",
						]);
					}

				}

				/*new code */
				$icons = $request->input('icons');
				if (count($icons) > 0) {
					foreach ($icons as $icon) {
						Package_Icon::create([
							'fkey' => $last_activity_id,
							'name' => $icon,
							'of' => "daytour",
						]);
					}
				}
				/*new code */

				$categories = $request->input('cat');
				if (count($categories) > 0) {

					foreach ($categories as $category) {
						Category::create([
							'fkey' => $last_activity_id,
							'name' => $category,
							'of' => "daytour",
						]);
					}
				}

				$cities = explode(",", $request->input('city'));
				if (count($cities) > 0) {

					foreach ($cities as $city) {
						City::create([
							'fkey' => $last_activity_id,
							'name' => $city,
							'of' => "daytour",
						]);
					}
					DB::table('cities')
						->where('fkey', $last_activity_id)
						->where('name', '')
						->delete();
				}
				return redirect()->route('daytour.add')->with('success', 'Daytour  added successfully ');

			} else {
				return redirect()->route('daytour.add')->with('error', 'Operation failed  ');
			}

		}

	}

	public function update($id) {
		$item_city[] = [];
		$item_category[] = [];
		$item_icon[] = [];

		$daytour = DB::table('daytours')
			->where('id', $id)
			->first();

		$citynames = DB::table('cities')
			->select('name')
			->where('fkey', $daytour->id)
			->get();

		$packagecat = DB::table('package_cat')
			->where('class', 'daytour')
			->get()
			->toArray();

		/*new code */
		$all_icons = DB::table('icons')
			->get()
			->toArray();
		$icons = DB::table('package_icons')
			->where('fkey', $id)
			->where('of', 'daytour')
			->get()
			->toArray();
		foreach ($icons as $item) {
			$item_icon[] = $item->name;
		}
		/*new code */

		$selected1 = DB::table('categories')
			->where('fkey', $id)
			->where('of', "daytour")
			->get()
			->toArray();

		foreach ($selected1 as $item) {
			$item_category[] = $item->name;
		}

		$selected = DB::table('countries')
			->where('fkey', $id)
			->where('of', "daytour")
			->get()
			->toArray();

		foreach ($selected as $item) {
			$item_city[] = $item->name;
		}

		$country_list = DB::table('country_list')
			->get()
			->toArray();

		if ($daytour) {
			return view('daytours/update')->with([

				'daytour' => $daytour,
				'packagecat' => $packagecat,
				'country_list' => $country_list,
				'item_city' => $item_city,
				'item_category' => $item_category,
				'citynames' => $citynames,
				/*new code */
				'item_icon' => $item_icon,
				'all_icons' => $all_icons,
				/*new code */

			]);

		} else {
			return redirect()->route('daytour.view')->with('error', 'Operation failed  ');
		}

	}

	public function edit(Request $request) {
		$validator = Validator::make($request->all(), [

			// 'name'                    =>'required',
			// 'city'                 =>'required',
			// 'country'                 =>'required',
			// 'cat'                  =>'required',
			// 'desc'                    =>'required',
			// 'file'                    =>'required',
			// 'img'                     =>'required',
			// 'about'                   =>'required',
			// 'day_detail'              =>'required',
			// 'duration'                =>'required',
			// 'disc'                    =>'required',
			// 'date'                    =>'required',
			// 'price'                   =>'required',
			// 'code'                    =>'required',
		]);

		if ($validator->fails()) {
			return redirect('/daytour/update')
				->withErrors($validator)
				->withInput();

		} else {
			$first_image = '';
			$first_file = '';

			$input = $request->all();
			$updated_activity_id = $request->input('id');

			if ($request->hasFile('img')) {
				$all_img = DB::table('images')
					->where('fkey', $updated_activity_id)
					->get();

				foreach ($all_img as $fil) {
					$db_path = $fil->src;
					$len = strlen($this->base_url . "/");
					$new_path = substr($db_path, $len, strlen($db_path) - $len);
					unlink($new_path);
				}

				$all_img = DB::table('images')
					->where('fkey', $updated_activity_id)
					->delete();

				$images = $request->file('img');
				foreach ($images as $img) {

					$extension = $img->getClientOriginalExtension();
					$image_name = time() . rand(1000, 9999) . "_." . $extension;
					$path = public_path('/storage/daytour_images/');
					$img->move($path, $image_name);
					$img_path = $this->base_url . '/public/storage/daytour_images/' . $image_name;

					$newimage = new Image;
					$newimage->fkey = $updated_activity_id;
					$newimage->src = $img_path;
					$newimage->name = $image_name;
					$newimage->save();

				}

			}

			if ($request->hasFile('file')) {

				$all_files = DB::table('files')
					->where('fkey', $updated_activity_id)
					->get();

				foreach ($all_files as $fil) {

					$db_path = $fil->src;
					$len = strlen($this->base_url . "/");
					$new_path = substr($db_path, $len, strlen($db_path) - $len);
					unlink($new_path);
				}

				$all_files = DB::table('files')
					->where('fkey', $updated_activity_id)
					->delete();

				$files = $request->file('file');

				foreach ($files as $file) {
					$extension = $file->getClientOriginalExtension();
					$file_name = time() . rand(1000, 9999) . "_." . $extension;
					$path = public_path('/storage/daytour_files/');
					$file->move($path, $file_name);
					$file_path = $this->base_url . '/public/storage/daytour_files/' . $file_name;

					$newimage = new File;
					$newimage->fkey = $updated_activity_id;
					$newimage->src = $file_path;
					$newimage->name = $file_name;
					$newimage->save();
				}

				$first_file = DB::table('files')
					->where('fkey', $updated_activity_id)
					->first();

			}

			$name = $request->input('name');
			$city = $request->input('city');
			$country = $request->input('country');
			$cat = $request->input('cat');
			$desc = $request->input('desc');
			$about = $request->input('about');
			$day_detail = $request->input('day_detail');
			$duration = $request->input('duration');
			$disc = $request->input('disc');
			$price = $request->input('price');
			$code = $request->input('code');
			// $img                     =$first_image;
			// $banner                  =$first_image;
			$terms = $request->input('terms');
			$payment_policy = $request->input('payment_policy');
			$payment_methods = $request->input('payment_methods');
			$cancellation_policy = $request->input('cancellation_policy');
			$visa_info = $request->input('visa_info');
			$notes = $request->input('notes');
			$questions = $request->input('questions');
			$grpsize = $request->input('grpsize');
			$tourcode = $request->input('tourcode');
			$destinations = $request->input('destinations');
			$startloc = $request->input('startloc');
			$tourstyle = $request->input('tourstyle');
			$tourlanguage = $request->input('tourlanguage');
			$avalibilitydetails = $request->input('avalibilitydetails');
			$tranportdetails = $request->input('tranportdetails');
			$accomodationdetails = $request->input('accomodationdetails');
			$mealdetails = $request->input('mealdetails');
			$guidedetails = $request->input('guidedetails');
			$status = $request->input('status');

			$updated_activity = DB::table('daytours')
				->where('id', $updated_activity_id)
				->update([

					'name' => $name,
					// 'city'                =>$city,
					// 'country'          =>$country,
					'desc' => $desc,
					'about' => $about,
					'day_detail' => $day_detail,
					'duration' => $duration,
					'disc' => $disc,
					'price' => $price,
					'code' => $code,
					// 'banner'           =>$banner,
					// 'img'              =>$img,

					'terms' => $terms,
					'payment_policy' => $payment_policy,
					'payment_methods' => $payment_methods,
					'cancellation_policy' => $cancellation_policy,
					'visa_info' => $visa_info,
					'notes' => $notes,
					'questions' => $questions,

					'grpsize' => $grpsize,
					'tourcode' => $tourcode,
					'destinations' => $destinations,
					'startloc' => $startloc,
					'tourstyle' => $tourstyle,
					'tourlanguage' => $tourlanguage,
					'avalibilitydetails' => $avalibilitydetails,
					'tranportdetails' => $tranportdetails,
					'accomodationdetails' => $accomodationdetails,
					'mealdetails' => $mealdetails,
					'cancellation_policy' => $cancellation_policy,
					'guidedetails' => $guidedetails,
					'status' => $status,
				]);

			if (count($countries = $request->input('country')) > 0) {
				DB::table('countries')
					->where('fkey', $updated_activity_id)
					->delete();

				foreach ($countries as $country) {

					Country::create([
						'fkey' => $updated_activity_id,
						'name' => $country,
						'of' => "daytour",

					]);
				}
			}

			/*new code */
			if (count($icons = $request->input('icons')) > 0) {
				DB::table('package_icons')
					->where('fkey', $updated_activity_id)
					->delete();
				foreach ($icons as $icon) {
					Package_Icon::create([
						'fkey' => $updated_activity_id,
						'name' => $icon,
						'of' => "daytour",
					]);
				}
			}
			/*new code */

			$categories = $request->input('cat');
			if (count($categories) > 0) {

				DB::table('categories')
					->where('fkey', $updated_activity_id)
					->delete();

				foreach ($categories as $category) {
					Category::create([
						'fkey' => $updated_activity_id,
						'name' => $category,
						'of' => "daytour",
					]);
				}
			}

			$cities = explode(",", $request->input('city'));
			if (count($cities) > 0) {

				DB::table('cities')
					->where('fkey', $updated_activity_id)
					->delete();

				foreach ($cities as $city) {
					City::create([
						'fkey' => $updated_activity_id,
						'name' => $city,
						'of' => "daytour",
					]);
				}
				DB::table('cities')
					->where('fkey', $updated_activity_id)
					->where('name', '')
					->delete();

			}
			if ($request->hasFile('img')) {

				$first_image = DB::table('images')
					->where('fkey', $updated_activity_id)
					->first();

				DB::table('daytours')
					->where('id', $updated_activity_id)
					->update([

						'banner' => $first_image->src,
						'img' => $first_image->src,

					]);
			}

			//cleanup

			if ($updated_activity) {

				return redirect()->route('daytour.view')->with('success', 'Daytour  Updated successfully ');

			} else {

				return redirect()->route('daytour.view')->with('error', 'Operation failed  ');

			}

		}

	}

	public function delete($id) {

		$activity = DB::table('daytours')
			->where('id', $id)
			->delete();

		$all_images = DB::table('images')
			->where('fkey', $id)
			->get();
		if ($all_images->count() > 0) {
			foreach ($all_images as $img) {
				$db_path = $img->src;
				$len = strlen($this->base_url . "/");
				$new_path = substr($db_path, $len, strlen($db_path) - $len);
				unlink($new_path);
			}

			DB::table('images')
				->where('fkey', $id)
				->delete();
		}

		$all_files = DB::table('files')
			->where('fkey', $id)
			->get();
		if ($all_files->count() > 0) {
			foreach ($all_files as $fil) {
				$db_path = $fil->src;
				$len = strlen($this->base_url . "/");
				$new_path = substr($db_path, $len, strlen($db_path) - $len);
				unlink($new_path);
			}

			DB::table('files')
				->where('fkey', $id)
				->delete();
		}

		/*new code */
		DB::table('package_icons')
			->where('fkey', $id)
			->delete();
		/*new code */

		DB::table('cities')
			->where('fkey', $id)
			->delete();

		DB::table('categories')
			->where('fkey', $id)
			->delete();

		DB::table('countries')
			->where('fkey', $id)
			->delete();

		if ($activity) {
			return redirect()->route('daytour.view')->with('success', 'Daytour  Deleted successfully ');

		} else {
			return redirect()->route('daytour.view')->with('error', 'Operation failed  ');
		}

	}

	public function category() {
		$package_cat = DB::table('package_cat')
			->where('class', 'daytour')
			->get();

		return view('daytours.category', [

			'package_cat' => $package_cat,
		]);
	}

	public function addcategory() {
		return view('daytours.addcategory');
	}

	public function insertcategory(request $request) {

		DB::table('package_cat')->insert(
			array(
				'class' => 'daytour',
				'name' => $request->input('name'),

			)

		);

		$package_cat = DB::table('package_cat')
			->where('class', 'daytour')
			->get();

		return view('daytours.category', [

			'package_cat' => $package_cat,
		]);

	}

	public function daytours() {
		$daytours = DB::table('daytours')->get();

		return view('daytours.packages')->with('daytours', $daytours);

	}

	public function DaytourDetails($id) {

		// $activity       = Daytour::with('getimage','getfile','getcity','getcountry','getcategory')->Where('id',$id)->first();
		//dd($activity);

		$daytour = DB::table('daytours')

			->where('id', $id)
			->first();

		$files = DB::table('files')
			->where('fkey', $id)
			->get();

		$images = DB::table('images')
			->where('fkey', $id)
			->get();

		$categories = DB::table('categories')
			->where('fkey', $id)
			->get();

		$countries = DB::table('countries')
			->where('fkey', $id)
			->get();

		$cities = DB::table('cities')
			->where('fkey', $id)
			->get();

		return view('daytours.detail')->with(
			[
				'daytour' => $daytour,
				'files' => $files,
				'images' => $images,
				'categories' => $categories,
				'countries' => $countries,
				'cities' => $cities,
			]);
	}

	public function gridView() {
		$daytours = Daytour::orderBy('id', 'asc')->where('status', '1')->paginate(3);

		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		// dd($cities);

		return
		view('daytours/grid')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,

		]);

	}

	public function listView() {
		$daytours = Daytour::orderBy('id', 'asc')->where('status', '1')->paginate(3);

		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */

		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);

	}

	public function search1($price) {
		$daytours = Daytour::with('geticons')
			->where('price', '>', '250')
			->paginate(3);
		$icons = Package_Icon::all();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'icons' => $icons,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,

		]);
	}
	public function search2($price) {
		$price1 = $price + 0;
		$daytours = Daytour::with('geticons')
			->where('price', '>=', '100')
			->where('price', '<=', '250')
			->paginate();
		$icons = Package_Icon::all();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'icons' => $icons,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,

		]);
	}
	public function search3($price) {
		// $price1=$price+1000;
		$daytours = DB::table('daytours')
			->where('price', '>=', '50')
			->where('price', '<=', '100')
			->paginate();
		$icons = Package_Icon::all();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'icons' => $icons,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,

		]);
	}
	public function search4($price) {
		// $price1=$price+1000;
		$daytours = DB::table('daytours')
			->where('price', '>=', '25')
			->where('price', '<=', '50')
			->paginate();
		$icons = Package_Icon::all();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'icons' => $icons,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,

		]);
	}
	public function search5($price) {
		// $price1=$price+1000;
		$daytours = DB::table('daytours')
			->where('price', '>=', '0')
			->where('price', '<=', '25')
			->paginate();
		$icons = Package_Icon::all();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'icons' => $icons,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			'success' => "Search results ",

		]);
	}
	public function searchByCity($city) {
		$daytours = DB::table('daytours')
			->join('cities', 'cities.fkey', '=', 'daytours.id')
			->where('cities.name', $city)
			->select('daytours.*')
			->paginate(3);
		$icons = Package_Icon::all();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();
		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		return view('/daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			'icons' => $icons,
			'success' => 'Results For City  -' . $city]);
	}
	public function searchByCountry($country) {
		$daytours = DB::table('daytours')
			->join('countries', 'countries.fkey', '=', 'daytours.id')
			->where('countries.name', $country)
			->select('daytours.*')
			->paginate(3);
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function searchByCategory($category) {
		$daytours = DB::table('daytours')
			->join('categories', 'categories.fkey', '=', 'daytours.id')
			->where('categories.name', $category)
			->select('daytours.*')
			->paginate(3);
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	//implementing search functions of grid view
	public function grid_search1($price) {
		$daytours = DB::table('daytours')
			->where('price', '>', '250')
			->paginate();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function grid_search2($price) {
		$price1 = $price + 1000;
		$daytours = DB::table('daytours')
			->where('price', '>=', '100')
			->where('price', '<=', '250')
			->paginate();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function grid_search3($price) {
		// $price1=$price+1000;
		$daytours = DB::table('daytours')
			->where('price', '>=', '50')
			->where('price', '<=', '100')
			->paginate();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function grid_search4($price) {
		// $price1=$price+1000;
		$daytours = DB::table('daytours')
			->where('price', '>=', '25')
			->where('price', '<=', '50')
			->paginate();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function grid_search5($price) {
		$daytours = DB::table('daytours')
			->where('price', '<', $price)
			->paginate();
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function grid_searchByCity($city) {
		$daytours = DB::table('daytours')
			->join('cities', 'cities.fkey', '=', 'daytours.id')
			->where('cities.name', $city)
			->select('daytours.*')
			->paginate(3);
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function grid_searchByCategory($category) {
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function grid_searchByCountry($country) {
		$daytours = DB::table('daytours')
			->join('countries', 'countries.fkey', '=', 'daytours.id')
			->where('countries.name', $country)
			->select('daytours.*')
			->paginate(3);
		$cities = DB::table('cities')->select('name')->distinct()->where('of', 'daytour')->distinct()->limit('5')->get();

		$countries = DB::table('countries')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();

		$categories = DB::table('categories')->select('name')->distinct()->where('of', 'daytour')->limit('5')->get();
		/*new code */
		$icons = DB::table('package_icons')->where('of', 'daytour')->get();
		/*new code */
		//  dd($daytours);
		return
		view('daytours/list')->with([
			'daytours' => $daytours,
			'cities' => $cities,
			'countries' => $countries,
			'categories' => $categories,
			/*new code */
			'icons' => $icons,
			/*new code */
		]);
	}
	public function returnpdf() {
		return view('daytours.pdf');
	}
	public function PDF($id) {
		$daytour = DB::table('daytours')
			->where('id', $id)
			->first();
		$pdf = PDF::loadView('daytours/pdf', compact('daytour'));
		$pdf->setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true]);
		return $pdf->download('weblinerz.pdf');
	}
	public function deletefile() {
		echo "deletefile";
	}
	public function deletecategory($id) {
		DB::table('package_cat')
			->where('id', $id)
			->delete();
		$package_cat = DB::table('package_cat')->where('class', 'daytour')->get();
		return view('daytours.category', [
			'package_cat' => $package_cat,
		]);
	}
}