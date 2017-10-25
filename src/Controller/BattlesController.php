<?php
namespace App\Controller;

use App\Controller\AppController;
use App\GoatBattle\Battle;
use App\GoatBattle\Quicky;
use App\GoatBattle\Pokey;

/**
 * Battle Controller
 *
 * @property \App\Model\Table\BattleTable $Battle
 */
class BattlesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $battle = $this->paginate($this->Battle);

        $this->set(compact('battle'));
        $this->set('_serialize', ['battle']);
    }

    /**
     * View method
     *
     * @param string|null $id Battle id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // $battle = $this->Battle->get($id, [
        //     'contain' => []
        // ]);

        
        $battle = new Battle(new Pokey(), new Quicky());
        $battle->go();
        $this->set('battle', $battle);
        // $this->set('_serialize', ['battle']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $battle = $this->Battle->newEntity();
        if ($this->request->is('post')) {
            $battle = $this->Battle->patchEntity($battle, $this->request->data);
            if ($this->Battle->save($battle)) {
                $this->Flash->success(__('The battle has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The battle could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('battle'));
        $this->set('_serialize', ['battle']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Battle id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $battle = $this->Battle->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $battle = $this->Battle->patchEntity($battle, $this->request->data);
            if ($this->Battle->save($battle)) {
                $this->Flash->success(__('The battle has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The battle could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('battle'));
        $this->set('_serialize', ['battle']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Battle id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $battle = $this->Battle->get($id);
        if ($this->Battle->delete($battle)) {
            $this->Flash->success(__('The battle has been deleted.'));
        } else {
            $this->Flash->error(__('The battle could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
