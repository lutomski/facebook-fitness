<?php

namespace App\Http\Controllers;

use App\FitnessActivity;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Facebook\Facebook;
use Illuminate\Http\Request;

use App\Http\Requests;

class FitnessController extends Controller
{
    /**
     * FitnessController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'auth.facebook']);

        $fb = new Facebook(
            [
                'app_id' => env('FACEBOOK_APP_ID'),
                'app_secret' => env('FACEBOOK_APP_SECRET'),
                'default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION'),
            ]
        );

        $this->fb = $fb;
    }

    /**
     * Display form to create new activity.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('create');
    }


    /**
     * Store information about activity.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'type' => 'required',
                'distance' => 'required|numeric|min:0.1',
                'calories' => 'required|integer|min:1',
                'duration' => 'required|numeric|min:1',
            ],
            [
                'type.required' => 'Wybierz typ aktywności.',
                'distance.required' => 'Wpisz przebyty dystans.',
                'distance.numeric' => 'Przebyty dystans musi być liczbą.',
                'distance.min' => 'Minimalny dystans to 0.1 km.',
                'calories.required' => 'Wpisz spalone kalorie.',
                'calories.integer' => 'Kalorie muszą być liczbą całkowitą.',
                'calories.min' => 'Minimalna wartość spalonych kalorii to 1.',
                'duration.required' => 'Wpisz czas aktywności.',
                'duration.numeric' => 'Czas aktywności musi być liczbą',
                'duration.min' => 'Minimalny czas aktywności to 1 minuta.',
            ]
        );

        $duration_in_seconds = round(request('duration') * 60);
        $now = Carbon::now();

        try {
        $this->fb->post(
            '/me/fitness.' . request('type'),
            [
                'course' => [
                    'og:title' => 'Sports Test App',
                    'fitness:calories' => request('calories'),
                    'fitness:distance:value' => request('distance'),
                    'fitness:distance:units' => 'km',
                    'fitness:duration:value' => $duration_in_seconds,
                    'fitness:duration:units' => 's'
                ],
                'start_time' => $now->timestamp
            ],
            Auth::user()->facebook_token
        );
        } catch (FacebookRequestException $ex) {
            return redirect()->action('HomeController@index')
                ->with('modal-title', 'Wystąpił błąd')
                ->with('modal-body', 'Wystąpił błąd: ' . $ex->getMessage());
        } catch (Exception $ex) {
            if ($ex->getCode() === 200) {
                return redirect()->action('HomeController@index')
                    ->with('modal-title', 'Wystąpił błąd')
                    ->with('modal-body', 'Wystąpił błąd uprawnień. Nie zezwoliłeś na publikację aktywności na swoim profilu. Zatwierdź mozliwość publikowania na Twoim proilu, aby móc dodać aktywność.<br>Aby dodać taką możliwość <a href="/auth/facebook">kliknij tutaj</a>.');
            } else {
                return redirect()->action('HomeController@index')
                    ->with('modal-title', 'Wystąpił błąd')
                    ->with('modal-body', 'Wystąpił błąd: ' . $ex->getMessage());
            }
        }

        FitnessActivity::create(
            [
                'user_id' => Auth::user()->id,
                'facebook_id' => null,
                'type' => request('type'),
                'distance' => request('distance'),
                'calories' => request('calories'),
                'duration' => $duration_in_seconds,
                'start_time' => $now
            ]
        );

        return redirect()->action('HomeController@index')
            ->with('modal-title', 'Dodano aktywność')
            ->with('modal-body', 'Dodano nową aktywność!');
    }

    /**
     * Get all fitness activities from Facebook API.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAll()
    {
        // delete all fitness activities assigned to user
        Auth::user()->fitnessActivities()->delete();

        // all fitness types
        $fitness_types = [
            'runs',
            'walks',
            'bikes'
        ];

        // count activities
        $count = 0;

        // get all fitness records
        foreach ($fitness_types as $fitness_type) {
            try {
                $response = $this->fb->get(
                    '/me/fitness.' . $fitness_type . '/course',
                    Auth::user()->facebook_token
                );
            } catch (FacebookRequestException $ex) {
                return redirect()->action('HomeController@index')
                    ->with('modal-title', 'Wystąpił błąd')
                    ->with('modal-body', 'Wystąpił błąd: ' . $ex->getMessage());
            } catch (Exception $ex) {
                if ($ex->getCode() === 190) {
                    $user = Auth::user();

                    $user->facebook_id = null;
                    $user->facebook_token = null;
                    $user->save();

                    return redirect()->action('HomeController@index')
                        ->with('modal-title', 'Utracono połączenie z Facebookiem')
                        ->with(
                            'modal-body',
                            'Token wygasł. Prosimy o ponowne połączenie konta z Facebookiem w celu wygenerowania nowego tokenu.'
                        );
                } else {
                    return redirect()->action('HomeController@index')
                        ->with('modal-title', 'Wystąpił błąd')
                        ->with('modal-body', 'Wystąpił błąd: ' . $ex->getMessage());
                }
            }

            $graphEdge = $response->getGraphEdge()->asArray();
            $count += count($graphEdge);

            foreach ($graphEdge as $graphNode) {
                if (array_key_exists('calories', $graphNode['data'])) {
                    FitnessActivity::create(
                        [
                            'user_id' => Auth::user()->id,
                            'facebook_id' => $graphNode['id'],
                            'type' => $fitness_type,
                            'distance' => $graphNode['data']['distance']['value'],
                            'calories' => $graphNode['data']['calories'],
                            'duration' => $graphNode['data']['duration']['value'],
                            'start_time' => $graphNode['created_time']
                        ]
                    );
                }
            }
        }

        if ($count === 0) {
            return redirect()->action('HomeController@index')
                ->with('modal-title', 'Brak aktywności do pobrania')
                ->with('modal-body', 'Na Twoim koncie na Facebook\'u nie ma żadnych aktywności sportowych.');
        } else {
            return redirect()->action('HomeController@index')
                ->with('modal-title', 'Pobrano akywności')
                ->with('modal-body', 'Pobraliśmy najnowsze aktywności z Facebook\'a.');
        }
    }

    /**
     * Get best efficiency (calories/duration) training for each type of fitness.
     *
     * @return array
     */
    public function getBestEfficiency()
    {
        $user_id = Auth::user()->id;

        $run = DB::table('fitness_activities')
            ->select('id as activity_id', 'start_time', DB::raw("(select sum(`calories`/`duration`) from fitness_activities where id = `activity_id`) as kcal_per_second"))
            ->where('type', 'runs')
            ->where('user_id', $user_id)
            ->orderBy('kcal_per_second', 'desc')
            ->first();

        $walk = DB::table('fitness_activities')
            ->select('id as activity_id', 'start_time', DB::raw("(select sum(`calories`/`duration`) from fitness_activities where id = `activity_id`) as kcal_per_second"))
            ->where('type', 'walks')
            ->where('user_id', $user_id)
            ->orderBy('kcal_per_second', 'desc')
            ->first();

        $bike = DB::table('fitness_activities')
            ->select('id as activity_id', 'start_time', DB::raw("(select sum(`calories`/`duration`) from fitness_activities where id = `activity_id`) as kcal_per_second"))
            ->where('type', 'bikes')
            ->where('user_id', $user_id)
            ->orderBy('kcal_per_second', 'desc')
            ->first();

        $data = [
            'run' => $run,
            'walk' => $walk,
            'bike' => $bike
        ];

        return $data;
    }
}
