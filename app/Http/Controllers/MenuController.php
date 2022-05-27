<?php


namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Routing\Controller as BaseController;

class MenuController extends BaseController
{
    /*
    Requirements:
    - the eloquent expressions should result in EXACTLY one SQL query no matter the nesting level or the amount of menu items.
    - it should work for infinite level of depth (children of childrens children of childrens children, ...)
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - open the `app/Http/Controllers/MenuController` file
    - eager loading cannot load deeply nested relationships
    - a recursive function in php is needed to structure the query results
    - partial or not working answers also get graded so make sure you commit what you have


    Sample response on GET /menu:
    ```json
    [
        {
            "id": 1,
            "name": "All events",
            "url": "/events",
            "parent_id": null,
            "created_at": "2021-04-27T15:35:15.000000Z",
            "updated_at": "2021-04-27T15:35:15.000000Z",
            "children": [
                {
                    "id": 2,
                    "name": "Laracon",
                    "url": "/events/laracon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 3,
                            "name": "Illuminate your knowledge of the laravel code base",
                            "url": "/events/laracon/workshops/illuminate",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 4,
                            "name": "The new Eloquent - load more with less",
                            "url": "/events/laracon/workshops/eloquent",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                },
                {
                    "id": 5,
                    "name": "Reactcon",
                    "url": "/events/reactcon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 6,
                            "name": "#NoClass pure functional programming",
                            "url": "/events/reactcon/workshops/noclass",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 7,
                            "name": "Navigating the function jungle",
                            "url": "/events/reactcon/workshops/jungle",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                }
            ]
        }
    ]
     */

    public function getMenuItems() {
        //throw new \Exception('implement in coding task 3');

        $menus = MenuItem::get();
//return $menus;
        $child_events = [];

        foreach($menus as $m){
            if(empty($m->parent_id)){
                if(!isset($child_events[0])){
                    $child_events[0] = [];
                }
                $child_events[0][] = $m;
            }else{
                if(!isset($child_events[$m->parent_id])){
                    $child_events[$m->parent_id] = [];
                }
                $child_events[$m->parent_id][]=$m;
            }
        }
//return $child_events;
//        foreach ($menus as $m){
//            if(isset($child_events[$m->id]))
//                $m->children = $child_events[$m->id];
//            else
//                $m->children = [];
//
//        }
//
//        return $menus;

        //return $child_events;

        foreach($child_events[0] as $m){
            $this->arrangeChild($m, $child_events);
        }

        return $child_events[0];

    }


    private function arrangeChild($m, $child_events){
        if(isset($child_events[$m->id])){
            foreach($child_events[$m->id] as $m1){
                $this->arrangeChild($m1, $child_events);
            }
            $m->children = $child_events[$m->id];
        }else{
            $m->children = [];
        }

    }
}
