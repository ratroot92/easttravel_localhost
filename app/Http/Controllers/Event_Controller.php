<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\All_Events;
use DB;
use Illuminate\Support\Facades\Redirect;
use App\City;
use App\Country;
use App\Category;
use App\Package_Icon;
use App\Image;
use App\File;
use App\Country_All__Events;
use App\City_All__Events;
use App\Category_All__Events;

class Event_Controller extends Controller
{

    private $base_url;

    public function __construct()
    {

        $this->base_url = url('/');
    }
    public function viewAddEvent($action)
    {


        $countries_list = DB::table('country_list')->get();
        $events_categories = DB::table('package_cat')->groupby('name')->get();
        $icons = DB::table('icons')->get();
        $event_types = array("Activity", "Cruise", "Daytour", "Transfer", "Package");
        // dd($countries_list);
        return view('all_events/addEvent', [
            'action' => $action,
            'country_list' => $countries_list,
            'packagecat' => $events_categories,
            'icons' => $icons,
            'event_types' => $event_types,
        ]);
    }

    public function allEvents()
    {
        $All_Events = All_Events::all();
        return view('all_events/allEvents', ['All_Events' => $All_Events]);
    }

    public function viewupdateEvent($action, $id)
    {

        $Event = All_Events::Find($id);
        $countries_list = DB::table('country_list')->get();
        $events_categories = DB::table('package_cat')->groupby('name')->get();
        $icons = DB::table('icons')->get();
        $event_types = array("Activity", "Cruise", "Daytour", "Transfer", "Package");
        // dd($countries_list);
        return view('all_events/addEvent', [
            'action' => $action,
            'country_list' => $countries_list,
            'packagecat' => $events_categories,
            'icons' => $icons,
            'event_types' => $event_types,
            'Event' => $Event
        ]);
    }

    public function addEvent(Request $request)
    {

        $input            = $request->all();
        $New_Event = new All_Events;
        $New_Event->event_type = $request->input('event_type');
        $New_Event->event_name = $request->input('name');
        $New_Event->description = $request->input('desc');
        $New_Event->discount = $request->input('disc');
        $New_Event->price = $request->input('price');
        $New_Event->event_type = $request->input('event_type');
        $New_Event->inclusion = $request->input('inclusion');
        $New_Event->exclusion = $request->input('exclusion');
        $New_Event->code = $request->input('code');
        $New_Event->duration = $request->input('duration');
        // fill with actual later
        $New_Event->added_by = $request->input('startloc');
        //
        $New_Event->terms_conditions = $request->input('terms');
        $New_Event->payment_policy = $request->input('payment_policy');
        $New_Event->payment_methods = $request->input('payment_methods');
        $New_Event->cancellation_policy = $request->input('cancellation_policy');
        $New_Event->notes = $request->input('notes');
        $New_Event->questions = $request->input('questions');
        $New_Event->group_size = $request->input('grpsize');
        $New_Event->tour_code = $request->input('tourcode');
        $New_Event->destinations = $request->input('destinations');
        $New_Event->start_location = $request->input('startloc');
        $New_Event->end_location = $request->input('endloc');
        $New_Event->tour_style = $request->input('tourstyle');
        $New_Event->tour_language = $request->input('tourlanguage');
        $New_Event->visa_info = $request->input('visa_info');
        $New_Event->avalibility_details = $request->input('avalibilitydetails');
        $New_Event->transport_details = $request->input('tranportdetails');
        $New_Event->accomodation_details = $request->input('accomodationdetails');
        $New_Event->meals_details = $request->input('mealdetails');
        $New_Event->guide_details = $request->input('guidedetails');
        $New_Event->itinerary = $request->input('itinerary');
        $New_Event->status = $request->input('status');
        $New_Event->save();



        // MANT TO MANY CITIES
        $Uncleaned_Cities = explode(",", $request->input('city'));
        $Cleaned_Cities = array_filter(array_map('trim', $Uncleaned_Cities));
        $cities = array_map(function ($v) {
            return is_string($v) ? trim($v) : $v;
        }, $Cleaned_Cities);
        foreach ($cities as $city) {
            $New_City = new City;
            $New_City->name = $city;
            $New_City->of = $New_Event->event_type;
            $New_City->fkey = $New_Event->id;
            $New_City->save();
            $New_City_All__Events = new City_All__Events;
            $New_City_All__Events->city_id = $New_City->id;
            $New_City_All__Events->all__events_id = $New_Event->id;

            $New_City_All__Events->save();
        }

        // MANT TO MANY COUNTRIES
        $countries = $request->input('country');
        foreach ($countries as $country) {
            $New_Country = new Country;
            $New_Country->name = $country;
            $New_Country->of = $New_Event->event_type;
            $New_Country->fkey = $New_Event->id;
            $New_Country->save();
            $New_Country_All__Events = new Country_All__Events;
            $New_Country_All__Events->country_id = $New_Country->id;
            $New_Country_All__Events->all__events_id = $New_Event->id;
            $New_Country_All__Events->save();
        }


        // MANT TO MANY CATEGORIES
        $categories = $request->input('cat');
        foreach ($categories as $category) {
            $New_Category = new Category;
            $New_Category->name = $category;
            $New_Category->of = $New_Event->event_type;
            $New_Category->fkey = $New_Event->id;
            $New_Category->save();
            $New_Category_All__Events = new Category_All__Events;
            $New_Category_All__Events->category_id = $New_Category->id;
            $New_Category_All__Events->all__events_id = $New_Event->id;
            $New_Category_All__Events->save();
        }


        // ONE TO MANY ICONS
        $icons = $request->input('icons');
        foreach ($icons as $icon) {
            $New_Package_Icon = new Package_Icon;
            $New_Package_Icon->name = $icon;
            $New_Package_Icon->of = $New_Event->event_type;
            $New_Package_Icon->fkey = $New_Event->id;
            $New_Package_Icon->save();
        }



        // ONE TO MANY IMAAGES

        if ($images = $request->file('img')) {

            foreach ($images as $key => $img) {

                //SAVE IMAGE TO LOCAL SERVER
                $extension  = $img->getClientOriginalExtension();
                $image_name = time() . rand(1000, 9999) . "_." . $extension;
                $path       = public_path('/storage/Event_Images/');
                $img->move($path, $image_name);
                $img_path       = $this->base_url . '/public/storage/Event_Images/' . $image_name;
                $New_Image = new Image;
                $New_Image->src = $img_path;
                $New_Image->name = $image_name;
                $New_Image->of = $New_Event->event_type;
                $New_Image->fkey = $New_Event->id;
                $New_Image->save();
                if ($key == 0) {
                    $Update_NewEvent_Banner = All_Events::where('id', $New_Event->id)->update(['banner' => $img_path]);
                }
            }
        }



        //ONE TO MANY FILES
        if ($files = $request->file('file')) {

            foreach ($files as $key => $file) {

                //SAVE FILE TO LOCAL SERVER
                $extension  = $file->getClientOriginalExtension();
                $file_name = time() . rand(1000, 9999) . "_." . $extension;
                $path       = public_path('/storage/Event_Files/');
                $file->move($path, $file_name);
                $file_path       = $this->base_url . '/public/storage/Event_Files/' . $file_name;
                $New_File = new File;
                $New_File->src = $file_path;
                $New_File->name = $file_name;
                $New_File->of = $New_Event->event_type;
                $New_File->fkey = $New_Event->id;
                $New_File->save();
            }
        }



        return redirect()->back()->with('success', '' . $New_Event->event_type . ' added successfully ');
    }
    // END ADD EVENT



    // UPDATE EVENT
    public function updateEvent(Request $request)
    {


        $input            = $request->all();
        $server_name = $this->base_url;

        // DELETE COUNTRIES
        $Updateable_Event = All_Events::Find($request->input('event_id'));
        if ($Updateable_Event) {
            ########################################
            // DELETE ALL FILES (ONE TO MANY )
            // UNLINK
            $All_Files = $Updateable_Event->GET_Files;
            if ($All_Files->count() > 0) {


                foreach ($All_Files as $fil) {
                    $db_path  = $fil->src;
                    $len      = strlen($this->base_url . "/");
                    $new_path = substr($db_path, $len, strlen($db_path) - $len);

                    if (strstr($server_name, 'localhost')) {
                        $new_path = str_replace("public/", "", $new_path);
                    }
                    unlink($new_path);
                }
            }

            // UNLINK END
            $Updateable_Event->GET_Files()->delete();
            ########################################
            // DELETE ALL IMAGES (ONE TO MANY )
            // UNLINK
            $All_Images = $Updateable_Event->GET_Images;
            if ($All_Images->count() > 0) {

                foreach ($All_Images as $img) {
                    $db_path  = $img->src;
                    $len      = strlen($this->base_url . "/");
                    $new_path = substr($db_path, $len, strlen($db_path) - $len);

                    if (strstr($server_name, 'localhost')) {
                        $new_path = str_replace("public/", "", $new_path);
                    }
                    unlink($new_path);
                }
            }

            // UNLINK END
            $Updateable_Event->GET_Images()->delete();
            ########################################
            // DELETE ALL ICONS (ONE TO MANY )
            $Updateable_Event->GET_Icons()->delete();
            ########################################
            // DELETE ALL COUNTRIES (MANY TO MANY )
            $Updateable_Event->GetActivityCountry()->delete();
            $Updateable_Event->GetActivityCountry()->sync([]);
            ########################################
            // DELETE ALL CITIES (MANY TO MANY )
            $Updateable_Event->GetActivityCity()->delete();
            $Updateable_Event->GetActivityCity()->sync([]);
            ########################################
            // DELETE ALL CATEGORIES (MANY TO MANY )
            $Updateable_Event->GetActivityCategory()->delete();
            $Updateable_Event->GetActivityCategory()->sync([]);
            ########################################

            // UPDATE EVENT
            $Updateable_Event->update([
                'event_type' => $request->input('event_type'),
                'event_name' => $request->input('name'),
                'description' => $request->input('desc'),
                'discount' => $request->input('disc'),
                'price' => $request->input('price'),
                'event_type' => $request->input('event_type'),
                'inclusion' => $request->input('inclusion'),
                'exclusion' => $request->input('exclusion'),
                'code' => $request->input('code'),
                'duration' => $request->input('duration'),
                // fill with actual later
                'added_by' => $request->input('startloc'),
                //
                'terms_conditions' => $request->input('terms'),
                'payment_policy' => $request->input('payment_policy'),
                'payment_methods' => $request->input('payment_methods'),
                'cancellation_policy' => $request->input('cancellation_policy'),
                'notes' => $request->input('notes'),
                'questions' => $request->input('questions'),
                'group_size' => $request->input('grpsize'),
                'tour_code' => $request->input('tourcode'),
                'destinations' => $request->input('destinations'),
                'start_location' => $request->input('startloc'),
                'end_location' => $request->input('endloc'),
                'tour_style' => $request->input('tourstyle'),
                'tour_language' => $request->input('tourlanguage'),
                'visa_info' => $request->input('visa_info'),
                'avalibility_details' => $request->input('avalibilitydetails'),
                'transport_details' => $request->input('tranportdetails'),
                'accomodation_details' => $request->input('accomodationdetails'),
                'meals_details' => $request->input('mealdetails'),
                'guide_details' => $request->input('guidedetails'),
                'itinerary' => $request->input('itinerary'),
                'status' => $request->input('status')
            ]);

            // MANT TO MANY CITIES
            $Uncleaned_Cities = explode(",", $request->input('city'));
            $Cleaned_Cities = array_filter(array_map('trim', $Uncleaned_Cities));
            $cities = array_map(function ($v) {
                return is_string($v) ? trim($v) : $v;
            }, $Cleaned_Cities);

            foreach ($cities as $city) {
                $New_City = new City;
                $New_City->name = $city;
                $New_City->of = $Updateable_Event->event_type;
                $New_City->fkey = $Updateable_Event->id;
                $New_City->save();
                $New_City_All__Events = new City_All__Events;
                $New_City_All__Events->city_id = $New_City->id;
                $New_City_All__Events->all__events_id = $Updateable_Event->id;

                $New_City_All__Events->save();
            }

            // MANT TO MANY COUNTRIES
            $countries = $request->input('country');
            foreach ($countries as $country) {
                $New_Country = new Country;
                $New_Country->name = $country;
                $New_Country->of = $Updateable_Event->event_type;
                $New_Country->fkey = $Updateable_Event->id;
                $New_Country->save();
                $New_Country_All__Events = new Country_All__Events;
                $New_Country_All__Events->country_id = $New_Country->id;
                $New_Country_All__Events->all__events_id = $Updateable_Event->id;
                $New_Country_All__Events->save();
            }


            // MANT TO MANY CATEGORIES
            $categories = $request->input('cat');
            foreach ($categories as $category) {
                $New_Category = new Category;
                $New_Category->name = $category;
                $New_Category->of = $Updateable_Event->event_type;
                $New_Category->fkey = $Updateable_Event->id;
                $New_Category->save();
                $New_Category_All__Events = new Category_All__Events;
                $New_Category_All__Events->category_id = $New_Category->id;
                $New_Category_All__Events->all__events_id = $Updateable_Event->id;
                $New_Category_All__Events->save();
            }


            // ONE TO MANY ICONS
            $icons = $request->input('icons');
            foreach ($icons as $icon) {
                $New_Package_Icon = new Package_Icon;
                $New_Package_Icon->name = $icon;
                $New_Package_Icon->of = $Updateable_Event->event_type;
                $New_Package_Icon->fkey = $Updateable_Event->id;
                $New_Package_Icon->save();
            }



            // ONE TO MANY IMAAGES

            if ($images = $request->file('img')) {

                foreach ($images as $key => $img) {

                    //SAVE IMAGE TO LOCAL SERVER
                    $extension  = $img->getClientOriginalExtension();
                    $image_name = time() . rand(1000, 9999) . "_." . $extension;
                    $path       = public_path('/storage/Event_Images/');
                    $img->move($path, $image_name);
                    $img_path       = $this->base_url . '/public/storage/Event_Images/' . $image_name;
                    $New_Image = new Image;
                    $New_Image->src = $img_path;
                    $New_Image->name = $image_name;
                    $New_Image->of = $Updateable_Event->event_type;
                    $New_Image->fkey = $Updateable_Event->id;
                    $New_Image->save();
                    if ($key == 0) {
                        $Update_NewEvent_Banner = All_Events::where('id', $Updateable_Event->id)->update(['banner' => $img_path]);
                    }
                }
            }



            //ONE TO MANY FILES
            if ($files = $request->file('file')) {

                foreach ($files as $key => $file) {

                    //SAVE FILE TO LOCAL SERVER
                    $extension  = $file->getClientOriginalExtension();
                    $file_name = time() . rand(1000, 9999) . "_." . $extension;
                    $path       = public_path('/storage/Event_Files/');
                    $file->move($path, $file_name);
                    $file_path       = $this->base_url . '/public/storage/Event_Files/' . $file_name;
                    $New_File = new File;
                    $New_File->src = $file_path;
                    $New_File->name = $file_name;
                    $New_File->of = $Updateable_Event->event_type;
                    $New_File->fkey = $Updateable_Event->id;
                    $New_File->save();
                }
            }
            return redirect()->route('view.all.events');
        } else {
        }
    }

    // END UPDATE EVENT

    //DELETE EVENT START
    public function deleteEvent($id)
    {
        $server_name = $this->base_url;

        // DELETE COUNTRIES
        $Updateable_Event = All_Events::Find($id);
        if ($Updateable_Event) {
            ########################################
            // DELETE ALL FILES (ONE TO MANY )
            // UNLINK
            $All_Files = $Updateable_Event->GET_Files;
            if ($All_Files->count() > 0) {


                foreach ($All_Files as $fil) {
                    $db_path  = $fil->src;
                    $len      = strlen($this->base_url . "/");
                    $new_path = substr($db_path, $len, strlen($db_path) - $len);

                    if (strstr($server_name, 'localhost')) {
                        $new_path = str_replace("public/", "", $new_path);
                    }
                    unlink($new_path);
                }
            }

            // UNLINK END
            $Updateable_Event->GET_Files()->delete();
            ########################################
            // DELETE ALL IMAGES (ONE TO MANY )
            // UNLINK
            $All_Images = $Updateable_Event->GET_Images;
            if ($All_Images->count() > 0) {

                foreach ($All_Images as $img) {
                    $db_path  = $img->src;
                    $len      = strlen($this->base_url . "/");
                    $new_path = substr($db_path, $len, strlen($db_path) - $len);

                    if (strstr($server_name, 'localhost')) {
                        $new_path = str_replace("public/", "", $new_path);
                    }
                    unlink($new_path);
                }
            }

            // UNLINK END
            $Updateable_Event->GET_Images()->delete();
            ########################################
            // DELETE ALL ICONS (ONE TO MANY )
            $Updateable_Event->GET_Icons()->delete();
            ########################################
            // DELETE ALL COUNTRIES (MANY TO MANY )
            $Updateable_Event->GetActivityCountry()->delete();
            $Updateable_Event->GetActivityCountry()->sync([]);
            ########################################
            // DELETE ALL CITIES (MANY TO MANY )
            $Updateable_Event->GetActivityCity()->delete();
            $Updateable_Event->GetActivityCity()->sync([]);
            ########################################
            // DELETE ALL CATEGORIES (MANY TO MANY )
            $Updateable_Event->GetActivityCategory()->delete();
            $Updateable_Event->GetActivityCategory()->sync([]);
            ########################################
        }
        if ($Updateable_Event->delete()) {
            return redirect()->route('view.all.events');
        }
    }
    // END DELETE EVENT
    // END DELETE EVENT
    public function eventDetail($id)
    {
        $Event = All_Events::Find($id);
        return view('all_events/eventDetail', ['Event' => $Event]);
    }
}