<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'is_parent' => $this->isParent(),
            'is_child' => $this->isChild(),
            'actions' => $this->actions()
        ];
    }

    public function actions()
    {
        if ($this->status === 1) {
            return array(
                [
                    'title' => 'Editar',
                    'url'=> null,
                    'action' => 'edit',
                    'icon' => 'img:vectors/edit4.png',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Eliminar',
                    'url'=> null,
                    'action' => 'delete',
                    'icon' => 'img:vectors/trash1.png',
                ],
                [
                    'title' => 'Deshabilitar',
                    'url'=> null,
                    'action' => 'changeStatus',
                    'vueEmit' => true,
                    'icon' => 'lock',
                    'color' => 'negative',
                    'type' => 'toggle'
                ]
            );
        } else {
            return array(
                [
                    'title' => 'Editar',
                    'url'=> null,
                    'action' => 'edit',
                    'icon' => 'img:vectors/edit4.png',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Eliminar',
                    'url'=> null,
                    'action' => 'delete',
                    'icon' => 'img:vectors/trash1.png',
                ],
                [
                    'title' => 'Habilitar',
                    'url'=> null,
                    'action' => 'changeStatus',
                    'vueEmit' => true,
                    'icon' => 'lock',
                    'color' => 'positive',
                    'type' => 'toggle'
                ]
            );
        }
    }
}
